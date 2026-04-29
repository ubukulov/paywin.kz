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

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        DB::beginTransaction();
        try {
            // 1. Обновляем основные поля
            $product->update([
                'sku' => $request->article,
                'name' => $request->name,
                'description' => $request->description,
                'product_category_id' => $request->product_category_id,
            ]);

            // 2. Удаляем фото, которые пользователь пометил на удаление
            $removedIds = json_decode($request->removed_photos, true);
            if(!empty($removedIds)) {
                $imagesToDelete = ProductImage::whereIn('id', $removedIds)->get();
                foreach($imagesToDelete as $img) {
                    if (Storage::disk('public')->exists($img->path)) {
                        Storage::disk('public')->delete($img->path);
                    }
                    $img->delete();
                }
            }

            // 3. Обрабатываем НОВЫЕ файлы
            // Создадим массив путей для новых фото, чтобы потом сопоставить их с порядком
            $newUploadedPaths = [];
            if ($request->hasFile('new_photos')) {
                $folder = 'products/' . date('Y-m');
                foreach ($request->file('new_photos') as $file) {
                    $newUploadedPaths[] = $file->store($folder, 'public');
                }
            }

            // 4. ГЛАВНОЕ: Сохраняем порядок (сортировку)
            // images_order прилетает как: [{"id":1,"isNew":false}, {"id":null,"isNew":true}, ...]
            $imagesOrder = json_decode($request->images_order, true);

            if (!empty($imagesOrder)) {
                $newPhotoIndex = 0;

                foreach ($imagesOrder as $index => $item) {
                    $isMain = ($index === 0); // Самое первое фото в массиве — всегда главное (обложка)

                    if ($item['isNew'] === true) {
                        // Если это новое фото, создаем запись, берем путь из только что загруженных
                        if (isset($newUploadedPaths[$newPhotoIndex])) {
                            $product->images()->create([
                                'path'     => $newUploadedPaths[$newPhotoIndex],
                                'main'     => $isMain,
                                'position' => $index,
                            ]);
                            $newPhotoIndex++;
                        }
                    } else {
                        // Если это старое фото, просто обновляем его позицию и статус "главного"
                        ProductImage::where('id', $item['id'])->update([
                            'position' => $index,
                            'main'     => $isMain
                        ]);
                    }
                }
            }

            // 5. Обновляем склады
            $warehousesData = json_decode($request->warehouses, true);
            if (!empty($warehousesData)) {
                foreach($warehousesData as $wId => $data) {
                    $product->warehouses()->updateExistingPivot($wId, [
                        'price'    => $data['price'],
                        'quantity' => $data['count'],
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $exception) {
            DB::rollback();
            // В продакшене лучше логировать $exception->getMessage()
            return response()->json(['error' => 'Ошибка при сохранении'], 500);
        }
    }
}
