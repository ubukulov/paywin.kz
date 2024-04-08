<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('welcome', 'AuthController@welcome')->name('welcome');
Route::get('/register', 'AuthController@register')->name('register');
Route::get('/login', 'AuthController@login')->name('login');
Route::post('/registration', 'AuthController@registration')->name('registration');
Route::get('/logout', 'AuthController@logout')->name('logout');
Route::post('/authentication', 'AuthController@authenticate')->name('authenticate');
Route::get('/how-it-works', 'IndexController@howItWorks')->name('howItWorks');
Route::get('about-us', 'IndexController@aboutUs')->name('aboutUs');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', 'IndexController@home')->name('home');
    Route::get('/prizes', 'IndexController@prizes')->name('prizes');
    Route::get('success/payment', 'IndexController@paymentSuccess')->name('payment.success');
    Route::get('error/payment', 'IndexController@paymentError')->name('payment.error');
    Route::post('/payment','IndexController@payment')->name('payment');
    Route::get('category/{slug}', 'CategoryController@show')->name('category.show');
    Route::get('category/{slug}/{id}', 'CategoryController@showPartner')->name('showPartner');
    Route::get('payment/{slug}/{id}/page', 'IndexController@paymentPage')->name('paymentPage');

    Route::get('review', 'IndexController@review')->name('review');
    Route::get('partner/{id}/not-given-prize', 'IndexController@notGivenPrize')->name('notGivenPrize');

    # Partner's route
    Route::group(['prefix' => 'partner', 'middleware' => ['partner'], 'namespace' => 'Partner', 'as' => 'partner.'], function(){
        Route::get('/', 'PartnerController@cabinet')->name('cabinet');
        Route::resource('/my-shares','ShareController');
        Route::get('qr', 'PartnerController@qr')->name('qr');
        Route::get('clients', 'PartnerController@clients')->name('clients');
        Route::get('edit', 'PartnerController@edit')->name('edit');
        Route::post('profile/update', 'PartnerController@profileUpdate')->name('profileUpdate');
        Route::get('address/create', 'PartnerController@addressCreate')->name('addressCreate');
        Route::post('address/store', 'PartnerController@addressStore')->name('addressStore');

        Route::get('images/create', 'PartnerController@imageCreate')->name('imageCreate');
        Route::post('images/store', 'PartnerController@imageStore')->name('imageStore');
    });

    # Users route
    Route::group(['prefix' => 'user', 'middleware' => ['user'], 'namespace' => 'User', 'as' => 'user.'], function(){
        Route::get('/', 'UserController@cabinet')->name('cabinet');
        Route::get('add-my-card', 'UserController@addMyCard')->name('addMyCard');
        Route::get('remove-my-card', 'UserController@removeMyCard');
        Route::get('earn', 'UserController@earn')->name('earn');
        Route::get('history', 'UserController@history')->name('history');
        //Route::get('');

        Route::group(['prefix' => 'settings'], function(){
            Route::get('/', 'UserController@settings')->name('settings');
            Route::get('/profile', 'SettingController@profile')->name('setting.profile');
            Route::post('profile/update', 'SettingController@profileUpdate')->name('setting.profileUpdate');
            Route::get('/change-password', 'SettingController@passwordChangeForm')->name('setting.passwordChangeForm');
            Route::post('/change-password', 'SettingController@passwordUpdate')->name('setting.passwordUpdate');
            Route::get('/{prize_id}/get-my-prize', 'UserController@getMyPrize')->name('getMyPrize');
        });
    });
});
