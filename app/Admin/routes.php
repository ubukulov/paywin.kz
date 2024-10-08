<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('/categories', 'HomeController@index')->name('home');
    $router->resource('prizes', 'PrizeController');
    $router->resource('shares', 'ShareController');
    $router->resource('partners', 'PartnerController');
});
