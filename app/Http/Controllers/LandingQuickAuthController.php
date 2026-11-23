<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Classes\SendPulse;

class LandingQuickAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'product_id' => 'nullable|integer|exists:products,id',
        ], [
            'name.required'  => 'Укажите ваше имя',
            'email.required' => 'Укажите ваш Email',
            'email.email'    => 'Введите корректный Email адрес',
        ]);

        $email = strtolower(trim($validated['email']));
        $name  = trim($validated['name']);

        $user = User::firstWhere('email', $email);
        $isNew = false;

        if (!$user) {
            $password = rand(1000, 9999);

            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make(Str::random(16)),
            ]);
            $isNew = true;
            SendPulse::sendEmail("Регистрация прошло успешно", 1962207, $user, ['password' => $password]);
        }

        Auth::login($user, true);
        $currentCity = app(\App\Services\CityService::class)->getCurrentCity();
        if ($currentCity && !$user->city_id) {
            $user->city_id = $currentCity->id;
        }
        $user->touch(); // Обновляет updated_at, чтобы пользователь попал в окно последних 48 часов
        $request->session()->regenerate();

        // Формируем прямую ссылку на товар в Kaspi
        $merchantId = config('services.kaspi.id', '30364524');
        $kaspiUrl = "https://kaspi.kz/shop/";
        if (!empty($validated['product_id'])) {
            $product = Product::find($validated['product_id']);
            if ($product) {
                $kaspiUrl = !empty($product->sku)
                    ? "https://kaspi.kz/shop/p/-{$product->sku}/?ksWidget=true&m={$merchantId}&merchantID={$merchantId}"
                    : "https://kaspi.kz/shop/search/?text=" . urlencode($product->name) . "&ksWidget=true&m={$merchantId}&merchantID={$merchantId}";
            }
        }

        return response()->json([
            'success'    => true,
            'is_new'     => $isNew,
            'kaspi_url'  => $kaspiUrl,
            'csrf_token' => csrf_token(),
            'message'    => 'Готово! Перенаправляем в Kaspi...',
        ]);
    }
}
