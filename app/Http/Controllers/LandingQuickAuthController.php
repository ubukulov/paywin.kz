<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LandingQuickAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ], [
            'name.required'  => 'Укажите ваше имя',
            'email.required' => 'Укажите ваш Email',
            'email.email'    => 'Введите корректный Email адрес',
        ]);

        $email = strtolower(trim($validated['email']));
        $name  = trim($validated['name']);

        // Ищем существующего пользователя или создаем нового в фоне
        $user = User::firstWhere('email', $email);
        $isNew = false;

        if (!$user) {
            $user = User::create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make(Str::random(16)),
            ]);
            $isNew = true;
        }

        // Авторизуем пользователя в текущей сессии
        Auth::login($user, true);
        $request->session()->regenerate();

        return response()->json([
            'success'    => true,
            'is_new'     => $isNew,
            'user_name'  => $user->name,
            'csrf_token' => csrf_token(),
            'message'    => $isNew
                ? 'Вы успешно зарегистрированы! Теперь доступна покупка через Kaspi.'
                : 'С возвращением, ' . $user->name . '! Кнопка Kaspi открыта.',
        ]);
    }
}
