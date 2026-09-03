<?php

namespace App\Http\Controllers;

use App\Enums\TransactionEnum;
use App\Models\City;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductStock;
use App\Models\Share;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class IndexController extends BaseController
{
    public function home(Request $request)
    {
        $cityId = Cookie::get('selected_city_id') ?? City::query()->value('id');

        // 1. Получаем категории, содержащие активные товары
        $categories = ProductCategory::whereHas('products', function ($q) {
            $q->where('is_active', true);
        })->get();

        // 2. Подзапрос складов
        $stockSubquery = ProductStock::query()
            ->select([
                'product_stocks.product_id',
                'product_stocks.price',
                'product_stocks.quantity',
                'product_stocks.is_preorder',
                'product_stocks.delivery_days',
            ])
            ->join('partner_warehouses', 'partner_warehouses.id', '=', 'product_stocks.warehouse_id')
            ->where('partner_warehouses.city_id', $cityId)
            ->where(function ($q) {
                $q->where('product_stocks.quantity', '>', 0)
                    ->orWhere('product_stocks.is_preorder', true);
            })
            ->orderByRaw('CASE WHEN product_stocks.quantity > 0 THEN 0 ELSE 1 END')
            ->orderBy('product_stocks.price', 'asc');

        $query = Product::query()
            ->where('products.is_active', true)
            ->joinSub($stockSubquery, 'best_stock', function ($join) {
                $join->on('products.id', '=', 'best_stock.product_id');
            })
            ->select('products.*', 'best_stock.price', 'best_stock.quantity', 'best_stock.is_preorder', 'best_stock.delivery_days')
            ->distinct()
            ->with('mainImage')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // ФИЛЬТР ПО КАТЕГОРИИ
        if ($request->filled('category_id')) {
            $query->where('products.product_category_id', $request->input('category_id'));
        }

        // ФИЛЬТР ПОИСКА
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'LIKE', "%{$search}%")
                    ->orWhere('products.sku', 'LIKE', "%{$search}%");
            });
        }

        // СОРТИРОВКА
        if ($request->filled('sort')) {
            match ($request->input('sort')) {
                'price_asc'  => $query->orderBy('best_stock.price', 'asc'),
                'price_desc' => $query->orderBy('best_stock.price', 'desc'),
                default      => $query->latest('products.id'),
            };
        } else {
            $query->latest('products.id');
        }

        $products = $query->paginate(12)->appends($request->all());

        if ($request->ajax()) {
            return response()->json([
                'html'      => view('_partials._products', compact('products'))->render(),
                'next_page' => $products->nextPageUrl(),
            ]);
        }

        return view('home-products', compact('products', 'categories'));
    }

    public function paymentPage($slug, $id)
    {
        $partner = User::findOrFail($id);
        $user = User::findOrFail(Auth::user()->id);
        return view('payment', compact('slug', 'id', 'partner', 'user'));
    }

    public function aboutUs()
    {
        return view('about');
    }

    public function payment(Request $request)
    {
        $amount = $request->input('amount');
        $partner_id = $request->input('partner_id');
        $transaction_id = $request->input('transaction_id');

        $user = Auth::user();
        $partner = User::findOrFail($partner_id);
        $new_amount = $amount;


        $payment = Payment::create([
            'user_id' => $user->id, 'partner_id' => $partner_id, 'amount' => $amount, 'pg_status' => 'ok', 'pg_payment_id' => $transaction_id
        ]);

        $user->givePrize($partner->shares, $payment);

        return redirect()->route('payment.success');
    }

    public function paymentWithBalance(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'partner_id' => 'required|exists:users,id',
            'transaction_id' => 'nullable|string'
        ]);

        $amount = (float) $request->input('amount');
        $partnerId = $request->input('partner_id');

        $user = Auth::user();
        $partner = User::findOrFail($partnerId);

        try {
            return DB::transaction(function () use ($user, $partner, $amount) {

                // Проверка: хватает ли денег (на случай, если JS на фронте подменили)
                if ($user->balance < $amount) {
                    throw new \Exception('Недостаточно средств на балансе');
                }

                // 3. Списываем сумму с баланса пользователя
                $user->changeBalance(
                    -$amount,
                    TransactionEnum::ADJUSTMENT, // Тип "Корректировка/Оплата"
                    $partner,
                    "Оплата услуг партнера {$partner->partnerProfile?->company}",
                );

                // 4. Начисляем сумму в баланс партнера
                $partner->changeBalance(
                    $amount,
                    TransactionEnum::SALE_INCOME,
                    $user,
                    "Продажа услуг клиенту {$user->phone}",
                );

                return redirect()->route('payment.success');
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function paymentSuccess()
    {
        $user = Auth::user();

        $payment = Payment::where(['user_id' => $user->id, 'pg_status' => 'ok'])->orderBy('id', 'DESC')->first();

        $prize = $payment->prize;
        if($prize) {
            $share = Share::findOrFail($prize->share_id);
        } else {
            $share = null;
        }


        return view('thanks', compact('payment', 'prize', 'share'));
    }

    public function paymentError(Request $request)
    {
        $payment_id = $request->input('pg_order_id');
        $payment = Payment::findOrFail($payment_id);
        $payment->pg_status = 'error';
        $payment->save();

        dd("Ошибка во время оплаты", $request->all());
    }

    public function review()
    {
        return view('review');
    }

    public function notGivenPrize($id)
    {
        $partner = User::findOrFail($id);
        return view('not_given_prize');
    }

    public function howItWorks()
    {
        return view('how_it_works');
    }

    public function setCity(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id'
        ]);

        // Сохраняем ID города в куки на 30 дней (43200 минут)
        Cookie::queue('selected_city_id', $request->city_id, 43200);

        return response()->json(['success' => true]);
    }
}
