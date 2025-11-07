<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RewardsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderController extends Controller
{
    protected $rewards;

    public function __construct(RewardsService $rewards) {
        $this->rewards = $rewards;
    }

    public function store(Request $request)
    {
        // Предполагаем, что front валидирует корзину, цены сверяются сервером.
        $user = Auth::user();
        $items = $request->input('items'); // [{product_id, quantity}, ...]
        // Рассчитаем total на сервере
        $total = 0;
        $orderItems = [];
        foreach($items as $it){
            $product = Product::findOrFail($it['product_id']);
            $qty = (int)$it['quantity'];
            $price = $product->price;
            $total += $price * $qty;
            $orderItems[] = ['product_id'=>$product->id,'quantity'=>$qty,'price'=>$price];
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'=>$user? $user->id : null,
                'total'=>$total,
                'status'=>'paid', // или pending → paid после подтверждения платежа
            ]);

            foreach($orderItems as $oi){
                OrderItem::create(array_merge($oi,['order_id'=>$order->id]));
                // уменьшение стока, логика возврата и пр.
            }

            // Вызываем сервис вознаграждений
            $rewardsResult = $this->rewards->processOrder($order);

            DB::commit();

            return response()->json([
                'order' => $order->load('items'),
                'rewards' => $rewardsResult
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error'=>$e->getMessage()],500);
        }
    }

}
