<?php

namespace App\Http\Controllers;

use App\Enums\TransactionEnum;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Referral;
use App\Models\Share;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PartnerGiftService;
use App\Services\TipTopPayService;

class CheckoutController extends Controller
{
    protected PartnerGiftService $partnerGiftService;
    protected TipTopPayService $tipTopPayService;

    public function __construct(PartnerGiftService $partnerGiftService, TipTopPayService $tipTopPayService)
    {
        $this->partnerGiftService = $partnerGiftService;
        $this->tipTopPayService = $tipTopPayService;
    }

    public function index()
    {
        $user = Auth::user();

        // 1. Получаем корзину
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        // 2. Ищем активный промокод на скидку или подарок
        $activePromo = UserBalance::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('type', 'promocode')
            ->with('share')
            ->first();

        // 3. Инициализируем переменные (важно, чтобы они были даже если промокода нет)
        $discount = 0;

        if ($activePromo && $activePromo->share && $activePromo->share->promo === 'discount') {
            // Рассчитываем сумму скидки
            $discount = ($cart->total * $activePromo->share->size) / 100;
        }

        // 4. Итоговая сумма для оплаты
        $finalTotal = $cart->total - $discount;

        // 5. Получаем доступные подарки

        $ttpPublicId = env('TIPTOPPAY_PUBLIC_ID');

        // ПЕРЕДАЕМ ВСЕ ПЕРЕМЕННЫЕ В VIEW
        return view('checkout.index', compact(
            'cart',
            'ttpPublicId',
            'activePromo',
            'discount',
            'finalTotal'
        ));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $cryptogram = $request->get('cryptogram');
            $cart = Cart::where('user_id', Auth::id())->first();
            $total = $cart->total;
            $discountValue = 0;

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index');
            }

            // Создаём заказ
            $order = Order::create([
                'user_id' => auth()->id(),
                'subtotal' => $cart->subtotal,
                'discount' => $discountValue,
                'shipping_cost' => 0,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => 'card', // по умолчанию карта
                'shipping_method' => 'courier',
                'shipping_address' => $request->address,
            ]);

            // Сохраняем товары и уменьшаем сток
            foreach ($cart->items as $item) {
                $productStock = ProductStock::where([
                    'product_id' => $item->product_id,
                    'city_id' => 1
                ])->first();

                if ($productStock) {
                    $productStock->decrement('quantity', $item->quantity);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);
            }

            // Отправляем платёж в TipTopPay
            $data = [
                'amount' => $cart->total,
                'orderId' => $order->id,
                'cryptogram' => $cryptogram,
            ];

            $paymentResponse = $this->tipTopPayService->payment($data);

            // Проверяем результат
            if (!isset($paymentResponse['Success']) || !$paymentResponse['Success']) {
                $model = $paymentResponse['Model'] ?? null;

                // Если требуется 3DS
                if ($model && isset($model['Status']) && $model['Status'] === 'AwaitingAuthentication') {

                    $order->update([
                        'transaction_id' => $model['TransactionId']
                    ]);

                    DB::commit(); // транзакцию пока закрываем, так как заказ создан

                    return response()->json([
                        'status' => '3ds_required',
                        'acs_url' => $model['AcsUrl'] ?? null,
                        'pareq' => $model['PaReq'] ?? null,
                        'transaction_id' => $model['TransactionId'] ?? null,
                        'order_id' => $order->id
                    ]);
                }

                // Иначе платеж не прошел
                throw new \Exception('Платеж не прошел: ' . json_encode($paymentResponse, JSON_UNESCAPED_UNICODE));
            }

            // Платёж прошёл сразу
            $model = $paymentResponse['Model'] ?? null;

            if (!$model || $model['Status'] !== 'Completed') {
                throw new \Exception('Платеж не завершен.');
            }

            // Сохраняем весь ответ TipTopPay в meta
            $order->update([
                'meta' => json_encode($model, JSON_UNESCAPED_UNICODE),
                'status' => 'paid'
            ]);

            foreach ($order->items as $item) {
                $product = $item->product;
                $partner = $product->partner;

                if ($partner && $partner->user_type == 'partner') {
                    // Начисляем доход партнеру
                    $partner->changeBalance(
                        $item->total,
                        TransactionEnum::SALE_INCOME,
                        $order->user,
                        "Продажа: {$product->title} (Заказ #{$order->id})",
                        [
                            'bank_payment_id' => $model['TransactionId'] ?? null
                        ]
                    );
                }
            }

            // Очистка корзины
            $cart->items()->delete();
            $cart->delete();

            DB::commit();
            return redirect('/')->with('success', 'Ваш заказ принят!');

        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['error' => $exception->getMessage()], 500);
        }
    }

    public function handle3DS(Request $request)
    {
        $transactionId = $request->input('MD'); // MD = transaction id
        $pares = $request->input('PaRes');

        $tiptop = new TipTopPayService();
        $result = $tiptop->confirm3DS($transactionId, $pares);

        if ($result['Success'] && $result['Model']['Status'] === 'Completed') {
            $order = Order::where(['transaction_id' => $transactionId])->first();

            // обновляем заказ
            $order->update(['status' => 'paid']);

            // зафиксируем в транзакции
            $bankId = $result['Model']['Token'] ?? $result['Model']['TransactionId'];
            foreach ($order->items as $item) {
                $partner = $item->product->partner;
                if ($partner && $partner->user_type == 'partner') {
                    $partner->changeBalance(
                        $item->total,
                        TransactionEnum::SALE_INCOME,
                        $order->user,
                        "Продажа (3DS): {$item->product->title} (Заказ #{$order->id})",
                        [
                            'bank_payment_id' => $bankId
                        ]
                    );
                }
            }

            $cart = $order->user->cart;
            if ($cart) {
                $cart->items()->delete();
                $cart->delete();
            }

            // Редирект родительского окна на страницу успеха
            return response(
                '<!DOCTYPE html>
            <html>
            <head>
                <script>
                    window.top.location.href = "/checkout/success";
                </script>
            </head>
            <body></body>
            </html>'
            );
        }

        return response('FAILED', 200);
    }

    public function success()
    {
        return view('checkout.success');
    }
}
