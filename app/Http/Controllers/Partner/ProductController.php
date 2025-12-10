<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where(['user_id' => Auth::id()])
            ->selectRaw('products.*, product_stocks.price, product_stocks.quantity')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->get();
        return view('partner.product.index', compact('products'));
    }

    public function create()
    {
        $storePoints = Auth::user()->getStorePoints();
        return view('partner.product.create', compact('storePoints'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $data['name'], 'description' => $data['description'], 'sku' => $data['article'],
                'category_id' => 4, 'user_id' => Auth::id()
            ]);

            $points = json_decode($request->store_points, true);

            foreach ($points as $pointId => $data) {
                ProductStock::create([
                    'product_id'     => $product->id,
                    'city_id'        => 1,
                    'store_point_id' => $pointId,
                    'price'          => $data['price'],
                    'quantity'          => $data['count'],
                ]);
            }

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $file) {

                    // Формируем путь с годом и месяцем
                    $folder = 'products/' . date('Y-m');

                    // Сохраняем файл в storage/app/public/products/YYYY-MM
                    $path = $file->store($folder, 'public');

                    // Создаём запись в таблице product_images
                    $product->images()->create([
                        'product_id' => $product->id,
                        'path'     => $path,
                        'main'     => $index === 0, // первое фото — главное
                        'position' => $index,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
