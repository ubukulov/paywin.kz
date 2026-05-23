<?php

namespace App\Providers;

use App\Models\City;
use App\Models\ProductCategory;
use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.app', 'category.product'], function ($view) {
            $cartCount = 0;
            if (auth()->check()) {
                $cart = Cart::where('user_id', auth()->id())->first();
                $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
            }
            $view->with('cartCount', $cartCount);

            $cities = cache()->remember('app_cities', now()->addDay(), function () {
                return City::all();
            });
            $view->with('cities', $cities);

            $cityId = Cookie::get('selected_city_id');
            $currentCity = $cityId
                ? $cities->firstWhere('id', $cityId) // firstWhere ищет в коллекции $cities, а не в БД
                : $cities->first();
            $view->with('currentCity', $currentCity);
        });

        View::composer(['layouts.app', 'home-products'], function($view){
            $categories = cache()->remember('app_categories', now()->addHour(), function () {
                return ProductCategory::where('is_active', true) // если есть флаг активности
                ->orderBy('sort_order') // если есть сортировка
                ->get();
            });
            $view->with('categories', $categories);
        });
    }
}
