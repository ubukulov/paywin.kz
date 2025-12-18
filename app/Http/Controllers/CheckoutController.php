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

            $order = Order::create([
                'user_id' => auth()->id(),
                'subtotal' => $cart->subtotal,
                'discount' => 0,
                'shipping_cost' => 0,
                'total' => $cart->total,
                'status' => 'pending',
                'payment_method' => 'cash',
                'shipping_method' => 'courier',
                'shipping_address' => $request->address,
            ]);

            // сохраняем товары
            foreach ($cart->items as $item) {
                $productStock = ProductStock::where(['product_id' => $item->product_id, 'city_id' => 1])->first();

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

            $data = [
                'amount' => $cart->total,
                'orderId' => $order->id,
                'cryptogram' => $cryptogram,
            ];

            $paymentResponse = $this->tipTopPayService->payment($data);

            if (!isset($paymentResponse['Success']) || !$paymentResponse['Success']) {
                throw new \Exception('Платеж не прошел.' . $paymentResponse);
            }

            // Платеж прошёл, получаем модель транзакции
            $model = $result['Model'] ?? null;

            if (!$model || $model['Status'] !== 'Completed') {
                throw new \Exception('Платеж не завершен.');
            }

            Payment::create([
                'user_id' => auth()->id(),
                'partner_id' => null, // если есть партнёр
                'pg_payment_id' => $model['Token'] ?? $model['TransactionId'],
                'amount' => $model['Amount'] ?? $order->total,
                'pg_status' => 'ok',
            ]);

            $order->update([
                'meta' => $model
            ]);

            // 🎁 попытка выиграть подарок
            $winnerGift = $this->partnerGiftService->getAvailableGiftsForUser(Auth::id(), $cart->total);

            if ($winnerGift) {
                // пользователь выиграл подарок
                PartnerGiftAllocation::create([
                    'partner_gift_id' => $winnerGift->id,
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'status' => 'pending', // потом можно обновить на 'won'
                ]);
            } else {
                // пользователь не выиграл — фиксируем попытку
                PartnerGiftAllocation::create([
                    'partner_gift_id' => null,
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                    'status' => 'lost',
                ]);
            }

            // очистить корзину
            $cart->items()->delete();
            $cart->delete();

            DB::commit();
            return redirect('/')->with('success', 'Ваш заказ принят!');

        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['error' => $exception->getMessage()], 500);
        }
    }
}
