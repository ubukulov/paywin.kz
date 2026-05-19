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
        View::composer('*', function ($view) {
            $cart = (auth()->check()) ? Cart::where('user_id', auth()->id())->first() : null;
            $view->with('cartCount', $cart ? $cart->items()->sum('quantity') : 0);
            $view->with('cities', City::all());

            $cityId = Cookie::get('selected_city_id');
            $currentCity = $cityId
                ? City::find($cityId)
                : City::first();

            $view->with('currentCity', $currentCity);

            $categories = cache()->remember('app_categories', now()->addHour(), function () {
                return ProductCategory::where('is_active', true) // если есть флаг активности
                ->orderBy('sort_order') // если есть сортировка
                ->get();
            });
            $view->with('categories', $categories);
        });
    }
}
