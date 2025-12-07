<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Partner\PartnerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Partner\ShareController;
use App\Http\Controllers\User\SettingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Partner\ProductController as PartnerProductController;

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
Route::get('welcome', [AuthController::class, 'welcome'])->name('welcome');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/registration', [AuthController::class, 'registration'])->name('registration');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/authentication', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/how-it-works', [IndexController::class, 'howItWorks'])->name('howItWorks');
Route::get('about-us', [IndexController::class, 'aboutUs'])->name('aboutUs');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [IndexController::class, 'home'])->name('home');
    Route::get('/prizes', [IndexController::class, 'prizes'])->name('prizes');

    Route::get('success/payment', [IndexController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('error/payment', [IndexController::class, 'paymentError'])->name('payment.error');
    Route::post('/payment', [IndexController::class, 'payment'])->name('payment');

    Route::get('category/{slug}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('category/{slug}/{id}', [CategoryController::class, 'showPartner'])->name('showPartner');
    Route::get('category-all-partners', [CategoryController::class, 'allPartners'])->name('category.allPartners');

    Route::get('payment/{slug}/{id}/page', [IndexController::class, 'paymentPage'])->name('paymentPage');
    Route::get('product/{slug}', [ProductController::class, 'show'])->name('product.show');

    Route::get('review', [IndexController::class, 'review'])->name('review');
    Route::get('partner/{id}/not-given-prize', [IndexController::class, 'notGivenPrize'])->name('notGivenPrize');

    # Partner's route
    Route::group(['prefix' => 'partner', 'middleware' => ['partner'], 'as' => 'partner.'], function(){
        Route::get('/', [PartnerController::class, 'cabinet'])->name('cabinet');

        Route::resource('/my-shares', ShareController::class);
        Route::get('qr', [PartnerController::class, 'qr'])->name('qr');
        Route::get('clients', [PartnerController::class, 'clients'])->name('clients');
        Route::get('edit', [PartnerController::class, 'edit'])->name('edit');
        Route::post('profile/update', [PartnerController::class, 'profileUpdate'])->name('profileUpdate');

        Route::get('address/create', [PartnerController::class, 'addressCreate'])->name('addressCreate');
        Route::post('address/store', [PartnerController::class, 'addressStore'])->name('addressStore');

        Route::get('images/create', [PartnerController::class, 'imageCreate'])->name('imageCreate');
        Route::post('images/store', [PartnerController::class, 'imageStore'])->name('imageStore');
        Route::get('images/lists', [PartnerController::class, 'imageLists'])->name('imageLists');
        Route::get('image/{id}/delete', [PartnerController::class, 'imageDelete'])->name('imageDelete');

        # Products
        Route::group(['prefix' => 'products'], function(){
            Route::get('/', [PartnerProductController::class, 'index'])->name('product.index');
            Route::get('create', [PartnerProductController::class, 'create'])->name('product.create');
            Route::post('store', [PartnerProductController::class, 'store'])->name('product.store');
        });
    });

    # Users route
    Route::group(['prefix' => 'user', 'middleware' => ['user'], 'namespace' => 'User', 'as' => 'user.'], function(){
        Route::get('/', [UserController::class, 'cabinet'])->name('cabinet');
        Route::get('add-my-card', [UserController::class, 'addMyCard'])->name('addMyCard');
        Route::get('remove-my-card', [UserController::class, 'removeMyCard']);
        Route::get('earn', [UserController::class, 'earn'])->name('earn');
        Route::get('history', [UserController::class, 'history'])->name('history');
        Route::post('balance-replenishment', [UserController::class, 'balanceReplenishment'])->name('balanceReplenishment');

        Route::group(['prefix' => 'settings'], function () {

            Route::get('/', [UserController::class, 'settings'])->name('settings');

            Route::get('/profile', [SettingController::class, 'profile'])->name('setting.profile');
            Route::post('profile/update', [SettingController::class, 'profileUpdate'])->name('setting.profileUpdate');

            Route::get('/change-password', [SettingController::class, 'passwordChangeForm'])->name('setting.passwordChangeForm');
            Route::post('/change-password', [SettingController::class, 'passwordUpdate'])->name('setting.passwordUpdate');

            Route::get('/{prize_id}/get-my-prize', [UserController::class, 'getMyPrize'])->name('getMyPrize');
        });
    });

    # Cart
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateQuantity']);
    Route::delete('/cart/item/{id}', [CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);

    # Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});
