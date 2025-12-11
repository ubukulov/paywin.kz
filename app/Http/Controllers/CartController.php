<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Services\PartnerGiftService;

class CartController extends Controller
{
    protected PartnerGiftService $partnerGiftService;

    public function __construct(PartnerGiftService $partnerGiftService)
    {
        $this->partnerGiftService = $partnerGiftService;
    }

    // Получить корзину
    public function getCart()
    {
        $cart = $this->getOrCreateCart();
        $gift = $this->partnerGiftService->getAvailableGiftsForUser(Auth::id(), $cart->total);
        return view('cart.index', compact('cart', 'gift'));
    }

    // Добавить товар
    public function addToCart(Request $request)
    {
        $product = Product::where(['products.id' => $request->product_id])
                ->selectRaw('products.*, product_stocks.price, product_stocks.quantity')
                ->join('product_stocks', 'product_stocks.product_id', 'products.id')
                ->first();
        $cart = $this->getOrCreateCart();

        $item = CartItem::firstOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $product->id],
            ['quantity' => 0, 'price' => $product->price, 'total' => 0]
        );

        $item->quantity += $request->quantity ?? 1;
        $item->total = $item->price * $item->quantity;
        $item->save();

        $this->updateTotals($cart);

        // возвращаем JSON с количеством товаров
        return response()->json([
            'success' => true,
            'total_items' => $cart->items()->sum('quantity'),
            'cart_total' => $cart->total,
        ]);
    }

    // Изменить количество
    public function updateQuantity(Request $request)
    {
        $item = CartItem::findOrFail($request->item_id);
        $item->quantity = $request->quantity;
        $item->total = $item->price * $request->quantity;
        $item->save();

        $this->updateTotals($item->cart);
        return response()->json($item->cart->load('items.product'));
    }

    // Удалить товар
    public function removeItem($id)
    {
        $item = CartItem::findOrFail($id);
        $cart = $item->cart;
        $item->delete();

        $this->updateTotals($cart);
        return response()->json($cart->load('items.product'));
    }

    // Очистить корзину
    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();
        $this->updateTotals($cart);

        return response()->json($cart);
    }

    // ====== ВНУТРЕННИЕ МЕТОДЫ ======

    private function getOrCreateCart()
    {
        $session_id = session()->getId();

        return Cart::firstOrCreate(
            ['user_id' => Auth::id() ?? null],
            ['session_id' => $session_id],
            ['subtotal' => 0, 'total' => 0]
        );
    }

    private function updateTotals(Cart $cart)
    {
        $cart->subtotal = $cart->items()->sum('total');
        $cart->total = $cart->subtotal;
        $cart->save();
    }
}
