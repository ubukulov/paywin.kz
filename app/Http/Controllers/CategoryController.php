<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function show($slug)
    {
        $category = Category::whereSlug($slug)->first();
        if(!$category) abort(404);
        /*$partners = User::where(['user_type' => 'partner'])
            ->join('user_profile', 'user_profile.user_id', 'users.id')
            ->whereNotNull('user_profile.category_id')
            ->get();*/
        $partners = User::where(['user_type' => 'partner'])
            ->join('partner_profiles', 'partner_profiles.partner_id', 'users.id')
            ->where('partner_profiles.category_id', $category->id)
            ->get();

        if ($slug == 'tovary') {
            $products = Product::with('images')
                    ->join('product_stocks', 'product_stocks.product_id', 'products.id')
                    ->select('products.*', 'product_stocks.price', 'product_stocks.quantity')
                    ->get();

            return view('category.products', compact( 'products'));
        }

        return view('category.partners', compact('partners', 'slug'));
    }

    public function showPartner($slug, $id)
    {
        $partner = User::findOrFail($id);
        $partnerProfile = $partner->partnerProfile;
        $addresses = $partner->address;
        $arr = [];
        foreach($addresses as $item){
            $arr[$item->id] = $item->toArray();
            $arr[$item->id]['address'] = str_replace('<br>', '', $item->address);
        }

        $addresses = $arr;

        return view('category.partner', compact('partner', 'slug', 'id', 'partnerProfile', 'addresses'));
    }

    public function allPartners()
    {
        $partners = User::where(['user_type' => 'partner'])
            ->selectRaw('users.*')
            ->join('partner_profiles', 'partner_profiles.partner_id', 'users.id')
            ->get();

        return view('category.all-partners', compact('partners'));
    }

    public function products($slug)
    {
        // 1. Находим текущую выбранную категорию
        $category = ProductCategory::where('slug', $slug)->firstOrFail();

        // 2. Собираем массив ID категорий для фильтрации
        // Нам нужен ID самой категории + ID всех её подкатегорий
        $categoryIds = [$category->id];

        // Если у категории parent_id == 0, значит она родительская — соберем её детей
        if ($category->parent_id == 0) {
            $childrenIds = ProductCategory::where('parent_id', $category->id)
                ->where('is_active', true)
                ->pluck('id')
                ->toArray();

            // Объединяем ID родителя и детей в один массив
            $categoryIds = array_merge($categoryIds, $childrenIds);
        }

        // 3. Достаем товары, которые входят в собранный список ID
        $products = Product::query()
            ->whereIn('product_category_id', $categoryIds) // Ищем товары всей группы категорий
            ->where('is_active', true)
            ->with(['mainImage'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(12);

        // 3. Возвращаем тот же самый view, так как разметка идентична!
        return view('home-products', compact('category', 'products'));
    }
}
