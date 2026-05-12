<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Services\PartnerGiftService;
use Illuminate\Support\Facades\DB;

class CartController extends BaseController
{
    protected PartnerGiftService $partnerGiftService;

    public function __construct(PartnerGiftService $partnerGiftService)
    {
        $this->partnerGiftService = $partnerGiftService;
    }

    public function getCart()
    {
        $cart = $this->getOrCreateCart();
        $gifts = $this->partnerGiftService->getEligiblePrizes($cart->total);

        return view('cart.index', compact('cart', 'gifts'));
    }

    // Добавить товар
    public function addToCart(Request $request)
    {
        $product = Product::where(['products.id' => $request->product_id])
                ->selectRaw('products.*, product_stocks.price, product_stocks.quantity, product_stocks.is_preorder')
                ->join('product_stocks', 'product_stocks.product_id', 'products.id')
                ->first();

        $cart = $this->getOrCreateCart();

        /*if ($cart->items()->exists()) {
            $cartStatus = DB::table('cart_items')
                ->join('product_stocks', 'product_stocks.product_id', '=', 'cart_items.product_id')
                ->where('cart_items.cart_id', $cart->id)
                ->select('product_stocks.is_preorder')
                ->first();

            $isCartPreorder = (bool) ($cartStatus->is_preorder ?? false);

            // Сравниваем тип нового товара с тем, что уже в корзине
            if ((bool)$product->is_preorder !== $isCartPreorder) {
                return response()->json([
                    'success' => false,
                    'message' => $isCartPreorder
                        ? 'В корзине уже есть предзаказ. Обычные товары нужно покупать отдельно.'
                        : 'В корзине уже есть обычные товары. Предзаказ оформляется отдельной корзиной.'
                ], 422);
            }
        }*/

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
            'message' => 'Товар успешно добавлен'
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
        /*$session_id = session()->getId();

        return Cart::firstOrCreate(
            ['user_id' => Auth::id() ?? null],
            ['session_id' => $session_id],
            ['subtotal' => 0, 'total' => 0]
        );*/

        return Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'session_id' => session()->getId(),
                'subtotal' => 0,
                'total' => 0
            ]
        );
    }

    private function updateTotals(Cart $cart)
    {
        $cart->subtotal = $cart->items()->sum('total');
        $cart->total = $cart->subtotal;
        $cart->save();
    }
}
