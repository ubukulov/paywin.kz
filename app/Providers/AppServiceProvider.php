<?php

namespace App\Providers;

use App\Models\City;
use App\Models\ProductCategory;
use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use App\Services\CityService;

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
    public function boot(CityService $cityService)
    {
        View::composer(['layouts.app', 'category.product', 'checkout.index'], function ($view) use ($cityService) {
            $cartCount = 0;
            if (auth()->check()) {
                $cart = Cart::where('user_id', auth()->id())->first();
                $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
            }
            $view->with('cartCount', $cartCount);

            $view->with('cities', $cityService->getCities());
            $view->with('currentCity', $cityService->getCurrentCity());
        });

        View::composer(['layouts.app', 'partner.partner', 'partner.product.create', 'partner.product.edit'], function($view){
            $categories = Cache::remember('product_categories_all', 86400, function () {
                return ProductCategory::all();
            });

            $view->with('productCategories', $categories);
        });

    }
}
