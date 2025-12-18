<?php

namespace App\Http\Controllers;

use App\Models\PartnerGiftAllocation;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PartnerGiftService;
use App\Services\TipTopPayService;
use App\Models\Payment;

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
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart || $cart->items()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Корзина пуста');
        }

        $gift = $this->partnerGiftService
            ->getAvailableGiftsForUser(Auth::id(), $cart->total);

        $ttpPublicId = env('TIPTOPPAY_PUBLIC_ID');

        return view('checkout', compact('cart', 'gift', 'ttpPublicId'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $cryptogram = $request->get('cryptogram');
            $cart = Cart::where('user_id', Auth::id())->first();

            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index');
            }

            // Создаём заказ
            $order = Order::create([
                'user_id' => auth()->id(),
                'subtotal' => $cart->subtotal,
                'discount' => 0,
                'shipping_cost' => 0,
                'total' => $cart->total,
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

            // Сохраняем Payment
            Payment::create([
                'user_id' => auth()->id(),
                'partner_id' => null,
                'pg_payment_id' => $model['Token'] ?? $model['TransactionId'],
                'amount' => $model['Amount'] ?? $order->total,
                'pg_status' => 'ok',
            ]);

            // Сохраняем весь ответ TipTopPay в meta
            $order->update([
                'meta' => json_encode($model, JSON_UNESCAPED_UNICODE),
                'status' => 'paid'
            ]);

            // 🎁 обработка подарка
            $winnerGift = $this->partnerGiftService->getAvailableGiftsForUser(Auth::id(), $cart->total);

            if ($winnerGift) {
                PartnerGiftAllocation::create([
                    'partner_gift_id' => $winnerGift->id,
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'status' => 'pending', // можно обновить на 'won'
                ]);
            } else {
                PartnerGiftAllocation::create([
                    'partner_gift_id' => null,
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'status' => 'lost',
                ]);
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
        $transactionId = $request->input('MD');
        $pares = $request->input('PaRes');

        $tiptop = new TipTopPayService();
        $result = $tiptop->confirm3DS($transactionId, $pares);

        if ($result['Success'] && $result['Model']['Status'] === 'Completed') {
            // Сохраняем Payment
            Payment::create([
                'user_id' => auth()->id(),
                'partner_id' => null,
                'pg_payment_id' => $result['Model']['Token'] ?? $result['Model']['TransactionId'],
                'amount' => $result['Model']['Amount'],
                'pg_status' => 'ok',
            ]);

            return view('checkout.success'); // либо redirect
        }

        return view('checkout.failed', ['error' => $result]);
    }

    public function success()
    {
        return view('checkout.success');
    }
}
