<?php

namespace App\Http\Controllers;

use App\Enums\OrderEnum;
use App\Enums\TransactionEnum;
use App\Enums\UserDiscountEnum;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\UserDiscount;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Services\PartnerGiftService;
use App\Services\TipTopPayService;

class CheckoutController extends BaseController
{
    protected PartnerGiftService $partnerGiftService;
    protected TipTopPayService $tipTopPayService;

    // Константы для расчетов
    const BANK_FEE_PERCENT = 3;        // Фиксированная комиссия эквайринга
    const AGENT_SHARE_OF_NET = 0.7;    // 70% от чистого остатка агенту
    const PLATFORM_SHARE_OF_NET = 0.3; // 30% от чистого остатка нам

    public function __construct(PartnerGiftService $partnerGiftService, TipTopPayService $tipTopPayService)
    {
        $this->partnerGiftService = $partnerGiftService;
        $this->tipTopPayService = $tipTopPayService;
    }

    public function index()
    {
        $user = Auth::user();

        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $gifts = $this->partnerGiftService->getEligiblePrizes($cart->total);

        // Ищем активный промокод
        $activePromo = UserDiscount::where('user_id', $user->id)
            ->where('status', UserDiscountEnum::ACTIVE->value)
            ->where('valid_until', '>=', now())
            ->latest()
            ->first();

        $discount = 0;
        if ($activePromo) {
            $discount = ($cart->total * $activePromo->percent) / 100;
            if ($activePromo->amount > 0 && $discount > $activePromo->amount) {
                $discount = $activePromo->amount;
            }
        }

        $finalTotal = $cart->total - $discount;
        $ttpPublicId = env('TIPTOPPAY_PUBLIC_ID');

        return view('checkout.index', compact(
            'cart',
            'ttpPublicId',
            'activePromo',
            'discount',
            'finalTotal',
            'gifts'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->first();

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index');
            }

            // Находим промокод для привязки к заказу
            $activePromo = UserDiscount::where('user_id', $user->id)
                ->where('status', UserDiscountEnum::ACTIVE->value)
                ->where('valid_until', '>=', now())
                ->latest()
                ->first();

            $discountValue = 0;
            if ($activePromo) {
                $discountValue = ($cart->total * $activePromo->percent) / 100;
                if ($activePromo->amount > 0 && $discountValue > $activePromo->amount) {
                    $discountValue = $activePromo->amount;
                }
            }

            // Создаём заказ
            //$isPreorder = $cart->isPreorder();
            $order = Order::create([
                'user_id' => $user->id,
                'user_discount_id' => $activePromo ? $activePromo->id : null, // Сохраняем ID промокода
                'subtotal' => $cart->total,
                'discount' => $discountValue,
                'total' => $cart->total - $discountValue,
                'status' => OrderEnum::PENDING->value, //($isPreorder) ? OrderEnum::PREORDER->value  : OrderEnum::PENDING->value,
                'payment_method' => 'card',
                'shipping_address' => $request->address ?? 'Самовывоз',
            ]);

            foreach ($cart->items as $item) {
                // Уменьшаем сток
                /*if (!$isPreorder) {
                    ProductStock::where(['product_id' => $item->product_id, 'city_id' => 1])
                        ->decrement('quantity', $item->quantity);
                }*/

                $currentWarehouseId = $item->warehouse_id ?? 1;

                ProductStock::where(['product_id' => $item->product_id/*, 'city_id' => Cookie::get('selected_city_id')*/])
                    ->decrement('quantity', $item->quantity);

                $product = Product::find($item->product_id);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'partner_id'   => $product->partner_id, // или $product->partner_id (ID партнера-владельца)
                    'warehouse_id' => $currentWarehouseId,
                    'product_name' => $product->name,
                    'product_sku'  => $product->sku,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);
            }

            // Запрос в TipTopPay
            $paymentResponse = $this->tipTopPayService->payment([
                'amount' => $order->total,
                'orderId' => $order->id,
                'cryptogram' => $request->get('cryptogram'),
            ]);

            if (!isset($paymentResponse['Success']) || !$paymentResponse['Success']) {
                $model = $paymentResponse['Model'] ?? null;
                if ($model && ($model['Status'] ?? '') === 'AwaitingAuthentication') {
                    $mdId = $model['TransactionId'] ?? ($model['MD'] ?? $model['AcsTransactionId'] ?? null);

                    $order->update([
                        'data' => array_merge($order->data ?? [], [
                            'transaction_id' => $mdId
                        ])
                    ]);

                    DB::commit();
                    return response()->json([
                        'status' => '3ds_required',
                        'acs_url' => $model['AcsUrl'],
                        'pareq' => $model['PaReq'],
                        'transaction_id' => $mdId,
                        'order_id' => $order->id
                    ]);
                }
                throw new \Exception('Платеж отклонен банком');
            }

            // Успех без 3DS
            $this->finalizeOrder($order, $paymentResponse['Model']);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Заказ успешно оплачен!'
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Логика распределения вознаграждений и выдачи призов
     */
    private function finalizeOrder(Order $order, $paymentModel, $isPreorder = false)
    {
        $order->update([
            'meta' => json_encode($paymentModel, JSON_UNESCAPED_UNICODE),
            'status' => ($isPreorder) ? OrderEnum::PREORDER->value  : OrderEnum::PAID->value,
            'transaction_id' => $paymentModel['TransactionId'] ?? $order->transaction_id
        ]);

        $buyer = $order->user;
        $referrer = $buyer->referrer; // Агент
        $promo = $order->userDiscount; // Промокод (если был)

        foreach ($order->items as $item) {
            $product = $item->product;
            $partner = $product->partner;

            // Если партнер не выплачивает % (user_type не partner)
            // или нет промокода, то считаем комиссию 0%
            $partnerCommissionRate = ($partner && $partner->user_type == 'partner' && $promo)
                ? $promo->percent
                : 0;

            if ($partnerCommissionRate > 0) {
                // 1. Пул вознаграждения (например, 10%)
                $totalCommissionPool = $item->total * ($partnerCommissionRate / 100);

                // 2. Комиссия банка (3% от общей суммы товара)
                $bankFee = $item->total * (self::BANK_FEE_PERCENT / 100);

                // 3. Чистая прибыль к распределению (Партнерские % минус Банк)
                $netProfit = $totalCommissionPool - $bankFee;

                // Начисляем партнеру его долю (Сумма - Комиссия)
                $partnerIncome = $item->total - $totalCommissionPool;
                $partner->changeBalance(
                    $partnerIncome,
                    TransactionEnum::SALE_INCOME,
                    $buyer,
                    "Продажа: {$product->title}. Комиссия партнера: {$partnerCommissionRate}%",
                    ['bank_payment_id' => $paymentModel['TransactionId'] ?? null]
                );

                // 4. Начисляем Агенту его 70% от чистого остатка
                if ($referrer && $netProfit > 0) {
                    $agentIncome = $netProfit * self::AGENT_SHARE_OF_NET;
                    $agentEffectivePercent = ($partnerCommissionRate - self::BANK_FEE_PERCENT) * self::AGENT_SHARE_OF_NET;

                    $referrer->changeBalance(
                        $agentIncome,
                        TransactionEnum::REFERRAL,
                        $buyer,
                        "Агентское вознаграждение ({$agentEffectivePercent}%): {$product->title}",
                        ['order_id' => $order->id]
                    );
                }
            } else {
                // Если партнер НЕ выплачивает %, он получает всю сумму за товар за вычетом ТОЛЬКО банка
                $bankFee = $item->total * (self::BANK_FEE_PERCENT / 100);
                $partnerIncome = $item->total - $bankFee;

                if ($partner) {
                    $partner->changeBalance(
                        $partnerIncome,
                        TransactionEnum::SALE_INCOME,
                        $buyer,
                        "Продажа (без комиссии): {$product->title}",
                        ['bank_payment_id' => $paymentModel['TransactionId'] ?? null]
                    );
                }
            }

            // {{-- НАЧИСЛЕНИЕ ПРИЗА ЗА КАЖДЫЙ ОПЛАЧЕННЫЙ ТОВАР --}}
            if ($partner && $partner->shares()->exists()) {
                // Вызываем твой метод раздачи призов (или метод через Service, смотря как у тебя реализовано)
                // Передаем акции партнера и сам заказ в качестве источника (source)
                $buyer->givePrize($partner->shares, $order);
            }

            $platformPromotionService = app(\App\Services\PlatformPromotionService::class);
            $platformPromotionService->checkAndGiveGifts($buyer, 'purchase', $order);
        }

        // Погашаем промокод
        if ($promo) {
            $promo->update(['status' => UserDiscountEnum::USED->value]);
        }

        // Очистка корзины
        $buyer->cart?->items()->delete();
        $buyer->cart?->delete();
    }

    /*public function handle3DS(Request $request)
    {
        $transactionId = $request->input('MD');
        $pares = $request->input('PaRes');

        $result = (new TipTopPayService())->confirm3DS($transactionId, $pares);

        if ($result['Success'] && $result['Model']['Status'] === 'Completed') {
            DB::beginTransaction();
            try {
                $order = Order::where('transaction_id', $transactionId)->first();
                if ($order && $order->status !== 'paid') {
                    $this->finalizeOrder($order, $result['Model']);
                }
                DB::commit();
                return response('<!DOCTYPE html><html><head><script>window.top.location.href = "/checkout/success";</script></head></html>');
            } catch (\Exception $e) {
                DB::rollBack();
                return response('Error', 500);
            }
        }
        return response('FAILED', 200);
    }*/

    public function handle3DS(Request $request)
    {
        // Проверяем оба варианта, которые может прислать шлюз/банк
        $transactionId = $request->input('MD') ?? $request->input('TransactionId');
        $pares = $request->input('PaRes');

        // Логируем для дебага, чтобы видеть, что прилетает от реальных юзеров
        \Log::info('3DS Callback Data', $request->all());

        $result = (new TipTopPayService())->confirm3DS($transactionId, $pares);

        if ($result['Success'] && $result['Model']['Status'] === 'Completed') {
            DB::beginTransaction();
            try {
                $order = Order::where('data->transaction_id', $transactionId)->first();

                if (!$order) {
                    \Log::error("Order not found for transaction: " . $transactionId);
                    // Если заказ не найден, всё равно редиректим на успех,
                    // чтобы юзер не пугался белого экрана (а там разберемся)
                    return response('<!DOCTYPE html><html><head><script>window.top.location.href = "/checkout/success";</script></head></html>');
                }

                if ($order->status !== OrderEnum::PAID->value) {
                    $this->finalizeOrder($order, $result['Model']);
                }

                DB::commit();
                return response('<!DOCTYPE html><html><head><script>window.top.location.href = "/checkout/success";</script></head></html>');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("3DS Finalize Error: " . $e->getMessage());
                return response('Error', 500);
            }
        }

        \Log::error("3DS Confirmation Failed for Trans: " . $transactionId, $result);
        return response('FAILED', 200);
    }

    public function success()
    {
        $user = Auth::user();

        // Подтягиваем последние призы пользователя, созданные за последние 5 минут для этого заказа
        $gifts = \App\Models\UserGift::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->latest()
            ->get();

        return view('checkout.success', compact('gifts'));
    }
}
