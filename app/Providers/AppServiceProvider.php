<?php

namespace App\Providers;

use App\Models\City;
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
            $cart = Cart::where('session_id', session()->getId())->first();
            $view->with('cartCount', $cart ? $cart->items()->sum('quantity') : 0);
            $view->with('cities', City::all());

            $cityId = Cookie::get('selected_city_id');
            $currentCity = $cityId
                ? City::find($cityId)
                : City::first();

            $view->with('currentCity', $currentCity);
        });
    }
}
