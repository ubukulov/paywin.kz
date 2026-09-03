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
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('partner_id', Auth::id())
            ->selectRaw('products.*, product_stocks.price, product_stocks.quantity')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->with('mainImage')
            ->latest('products.id')
            ->paginate(50);

        return view('partner.product.index', compact('products'));
    }

    public function create()
    {
        $warehouses = Auth::user()->getWarehouses();
        return view('partner.product.create', compact('warehouses'));
    }

    public function store(Request $request)
    {
        // Проверка на случай превышения post_max_size на сервере
        if (empty($request->all())) {
            return response()->json([
                'error' => 'Общий размер загружаемых файлов слишком велик. Уменьшите размер фото.'
            ], 422);
        }

        $data = $request->all();
        $metaData = json_decode($request->input('meta', '{}'), true);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name'                => $data['name'],
                'description'         => $data['description'] ?? '',
                'sku'                 => $data['article'] ?? null,
                'product_category_id' => $data['product_category_id'],
                'partner_id'          => Auth::id(),
                'data'                => $metaData
            ]);

            $warehouses = json_decode($request->input('warehouses', '{}'), true);

            if (!empty($warehouses) && is_array($warehouses)) {
                foreach ($warehouses as $warehouseId => $wData) {
                    // Пропускаем склад, если цена не указана
                    if (!isset($wData['price']) || $wData['price'] === '' || $wData['price'] === null) {
                        continue;
                    }

                    $isPreorder = (bool) ($wData['is_preorder'] ?? false);
                    $deliveryDays = ($isPreorder && !empty($wData['delivery_days'])) ? (int) $wData['delivery_days'] : null;

                    ProductStock::create([
                        'product_id'    => $product->id,
                        'warehouse_id'  => $warehouseId,
                        'price'         => (float) $wData['price'],
                        'quantity'      => (int) ($wData['count'] ?? 0),
                        'is_preorder'   => $isPreorder,
                        'delivery_days' => $deliveryDays,
                    ]);
                }
            }

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $file) {
                    if ($file->isValid()) {
                        $folder = 'products/' . date('Y-m');
                        $path = $file->store($folder, 'public');

                        $product->images()->create([
                            'product_id' => $product->id,
                            'path'       => $path,
                            'main'       => $index === 0,
                            'position'   => $index,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            report($e);

            return response()->json([
                'error' => 'Ошибка при сохранении: ' . $e->getMessage()
            ], 500);
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
        $metaData = json_decode($request->input('meta', '{}'), true);

        DB::beginTransaction();
        try {
            // 1. Обновляем основные поля
            $product->update([
                'sku'                 => $request->article,
                'name'                => $request->name,
                'description'         => $request->description,
                'product_category_id' => $request->product_category_id,
                'is_active'           => $request->boolean('is_active'),
                'data'                => $metaData
            ]);

            // 2. Удаляем фото, помеченные на удаление
            $removedIds = json_decode($request->removed_photos, true);
            if (!empty($removedIds)) {
                $imagesToDelete = ProductImage::whereIn('id', $removedIds)->get();
                foreach ($imagesToDelete as $img) {
                    if (Storage::disk('public')->exists($img->path)) {
                        Storage::disk('public')->delete($img->path);
                    }
                    $img->delete();
                }
            }

            // 3. Обрабатываем НОВЫЕ файлы
            $newUploadedPaths = [];
            if ($request->hasFile('new_photos')) {
                $folder = 'products/' . date('Y-m');
                foreach ($request->file('new_photos') as $file) {
                    if ($file->isValid()) {
                        $newUploadedPaths[] = $file->store($folder, 'public');
                    }
                }
            }

            // 4. Сохраняем порядок фотографий
            $imagesOrder = json_decode($request->images_order, true);

            if (!empty($imagesOrder)) {
                $newPhotoIndex = 0;

                foreach ($imagesOrder as $index => $item) {
                    $isMain = ($index === 0);

                    if ($item['isNew'] === true) {
                        if (isset($newUploadedPaths[$newPhotoIndex])) {
                            $product->images()->create([
                                'path'     => $newUploadedPaths[$newPhotoIndex],
                                'main'     => $isMain,
                                'position' => $index,
                            ]);
                            $newPhotoIndex++;
                        }
                    } else {
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
                foreach ($warehousesData as $wId => $wData) {
                    if (!isset($wData['price']) || $wData['price'] === '' || $wData['price'] === null) {
                        continue;
                    }

                    $isPreorder = (bool) ($wData['is_preorder'] ?? false);
                    $deliveryDays = ($isPreorder && !empty($wData['delivery_days'])) ? (int) $wData['delivery_days'] : null;

                    $product->warehouses()->syncWithoutDetaching([
                        $wId => [
                            'price'         => (float) $wData['price'],
                            'quantity'      => (int) ($wData['count'] ?? 0),
                            'is_preorder'   => $isPreorder,
                            'delivery_days' => $deliveryDays,
                        ]
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $exception) {
            DB::rollback();
            report($exception);

            return response()->json(['error' => 'Ошибка при сохранении: ' . $exception->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $product = Product::where('partner_id', Auth::id())->findOrFail($id);

        // Удаляем изображения с диска
        foreach ($product->images as $img) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($img->path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($img->path);
            }
        }

        // Удаляем привязанные склады и сам товар
        $product->stocks()->delete();
        $product->images()->delete();
        $product->delete();

        return redirect()->back()->with('success', 'Товар успешно удален');
    }
}
