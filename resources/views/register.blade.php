@extends('layouts.auth')

@section('title', 'Регистрация')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4">

        <div class="">

            <div>
                <img class="logo" src="{{ asset('images/logo_bg_white.jpg') }}" alt="">
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                Регистрация
            </h1>

            <p class="text-gray-500 mb-6">
                Зарегистрируйтесь, используя номер телефона
            </p>

            <form action="{{ route('registration') }}" method="post" class="space-y-5">
                @csrf

                {{-- Телефон --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Номер телефона
                    </label>

                    <input
                        id="reg-1-phone-input"
                        type="tel"
                        name="phone"
                        required
                        placeholder="+7 ___ ___ __ __"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           outline-none transition"
                    >
                </div>

                {{-- Промокод --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">
                        Промокод
                    </label>

                    <input
                        type="text"
                        name="code"
                        placeholder="Необязательно"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           outline-none transition"
                    >
                </div>

                {{-- Чекбоксы --}}
                <div class="space-y-3">

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="partner" value="yes"
                               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700">
                        Стать партнером
                    </span>
                    </label>

                    <label class="flex items-center gap-3">
                        <input type="checkbox" checked disabled
                               class="w-5 h-5 rounded border-gray-300 text-blue-600">
                        <span class="text-sm text-gray-700">
                        Подтверждаю ознакомление и согласие с условиями
                        <a href="{{ asset('files/публичная_оферта.pdf') }}"
                           target="_blank"
                           class="text-blue-600 hover:underline">
                            Публичной оферты
                        </a>
                    </span>
                    </label>

                </div>

                {{-- Кнопки --}}
                <div class="flex items-center justify-between pt-4">

                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-500 hover:text-blue-600 transition">
                        Авторизация
                    </a>

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white
                               px-6 py-3 rounded-xl font-medium
                               transition shadow-md hover:shadow-lg">
                        Зарегистрироваться
                    </button>

                </div>
            </form>
        </div>
    </div>
@endsection


@push('js')
    <script>
        $(function(){
            $('#reg-1-phone-input').mask('+7 999-999-99-99');
        });
    </script>
@endpush
