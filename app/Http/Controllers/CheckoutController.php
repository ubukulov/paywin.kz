<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('session_id', session()->getId())->firstOrFail();
        return view('checkout', compact('cart'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $cart = Cart::where('session_id', session()->getId())->firstOrFail();

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
                /*$product = Product::find($item->product_id);
                $product->quantity -= $item->quantity;
                $product->save();*/

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
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
