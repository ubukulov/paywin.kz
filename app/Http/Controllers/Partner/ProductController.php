<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerWarehouse;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where(['partner_id' => Auth::id()])
            ->selectRaw('products.*, product_stocks.price, product_stocks.quantity')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->get();
        return view('partner.product.index', compact('products'));
    }

    public function create()
    {
        $warehouses = Auth::user()->getWarehouses();
        $productCategories = ProductCategory::all();
        return view('partner.product.create', compact('warehouses', 'productCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        DB::beginTransaction();
        try {
            $product = Product::create([
                'name' => $data['name'], 'description' => $data['description'], 'sku' => $data['article'],
                'product_category_id' => $data['product_category_id'], 'partner_id' => Auth::id()
            ]);

            $warehouses = json_decode($request->warehouses, true);

            foreach ($warehouses as $warehouseId => $data) {
                $partnerWarehouse = PartnerWarehouse::findOrFail($warehouseId);
                ProductStock::create([
                    'product_id'     => $product->id,
                    'city_id'        => $partnerWarehouse->city_id,
                    'warehouse_id'   => $warehouseId,
                    'price'          => $data['price'],
                    'quantity'       => $data['count'],
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

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $warehouses = Auth::user()->getWarehouses();
        $productCategories = ProductCategory::all();
        return view('partner.product.edit', compact('product', 'warehouses', 'productCategories'));
    }

    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);
        DB::beginTransaction();
        try {
            // Обновляем основные поля
            $product->update([
                'sku' => $request->article,
                'name' => $request->name,
                'description' => $request->description,
                'product_category_id' => $request->product_category_id,
            ]);

            // Удаляем фото, которые пользователь нажал "удалить"
            $removedIds = json_decode($request->removed_photos);
            if(!empty($removedIds)) {
                $images = ProductImage::whereIn('id', $removedIds)->get();
                foreach($images as $img) {
                    Storage::delete($img->path); // или как у тебя хранится путь
                    $img->delete();
                }
            }

            // Сохраняем новые фото
            if ($request->hasFile('new_photos')) {
                foreach ($request->file('new_photos') as $index => $file) {
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

            // Обновляем склады
            $warehousesData = json_decode($request->warehouses, true);
            foreach($warehousesData as $wId => $data) {
                $product->warehouses()->updateExistingPivot($wId, [
                    'price'    => $data['price'],
                    'quantity' => $data['count'], // У тебя в БД поле quantity, а из Vue летит count
                ]);
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
