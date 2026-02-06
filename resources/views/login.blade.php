@extends('layouts.auth')

@section('title', 'Авторизация')

@section('content')
    <div class="min-h-screen flex items-center justify-center">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">

            {{-- Лого --}}
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/logo_bg_white.jpg') }}"
                     class="h-16 object-contain"
                     alt="Logo">
            </div>

            <h1 class="text-2xl font-bold text-gray-800 text-center mb-2">
                Авторизация
            </h1>

            <p class="text-center text-gray-500 mb-6">
                Войдите, используя номер телефона
            </p>

            <form method="post" action="{{ route('authenticate') }}" class="space-y-5">
                @csrf

                {{-- Телефон --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Номер телефона
                    </label>

                    <input
                        id="auth-phone-input"
                        type="tel"
                        name="phone"
                        required
                        placeholder="+7 ___ ___ __ __"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           outline-none transition"
                    >
                </div>

                {{-- Пароль --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Пароль
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="Введите пароль"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           outline-none transition"
                    >
                </div>

                {{-- Кнопки --}}
                <div class="flex items-center justify-between pt-4">

                    <a href="{{ route('register') }}"
                       class="text-sm text-gray-500 hover:text-blue-600 transition">
                        Регистрация
                    </a>

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white
                               px-6 py-3 rounded-xl font-medium
                               transition shadow-md hover:shadow-lg">
                        Войти
                    </button>

                </div>
            </form>
        </div>
    </div>
@endsection


@push('js')
    <script>
        $(function(){
            $('#auth-phone-input').mask('+7 999-999-99-99');
        });
    </script>
@endpush
