@extends('layouts.app')

@section('content')
    <div class="w-full min-h-screen bg-white px-4 py-8 flex flex-col">

        {{-- Заголовок --}}
        <h1 class="text-4xl font-black text-gray-900 mb-12 leading-[1.1] animate__animated animate__fadeInLeft">
            Как это <br> <span class="text-[#18BE1E]">работает</span>
        </h1>

        {{-- Список шагов --}}
        <div class="space-y-6 w-full flex-1">

            {{-- Шаг 1 --}}
            <div class="flex items-center gap-5 p-5 bg-gray-50 rounded-[2rem] border border-gray-100 shadow-sm animate__animated animate__fadeInUp">
                <div class="w-20 h-20 flex-shrink-0 bg-white rounded-2xl shadow-sm flex items-center justify-center">
                    <img src="images/hiworks/phone.svg" alt="phone" class="w-10 h-10 text-[#18BE1E]">
                </div>
                <div class="text-lg font-bold text-gray-800 leading-tight">
                    Сделай заказ <br> и отсканируй QR код
                </div>
            </div>

            {{-- Шаг 2 --}}
            <div class="flex items-center gap-5 p-5 bg-gray-50 rounded-[2rem] border border-gray-100 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="w-20 h-20 flex-shrink-0 bg-white rounded-2xl shadow-sm flex items-center justify-center">
                    <img src="images/hiworks/card.svg" alt="card" class="w-10 h-10">
                </div>
                <div class="text-lg font-bold text-gray-800 leading-tight">
                    Оплати счёт <br> с баланса или карты
                </div>
            </div>

            {{-- Шаг 3 --}}
            <div class="flex items-center gap-5 p-5 bg-[#18BE1E]/5 border border-[#18BE1E]/10 rounded-[2rem] shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="w-20 h-20 flex-shrink-0 bg-[#18BE1E] rounded-2xl shadow-lg shadow-[#18BE1E]/30 flex items-center justify-center">
                    <img src="images/hiworks/winning-present.svg" alt="gift" class="w-10 h-10 brightness-0 invert">
                </div>
                <div class="text-lg font-bold text-gray-800 leading-tight">
                    Выиграй у партнёра <br> крутой подарок
                </div>
            </div>

        </div>

        {{-- Кнопка назад --}}
        <div class="mt-12">
            <a href="{{ route('home') }}"
               class="flex items-center justify-center gap-3 w-full py-5 bg-gray-900 text-white font-bold rounded-3xl shadow-xl hover:bg-gray-800 transition-all active:scale-[0.98] uppercase tracking-widest text-sm">
                <img src="images/hiworks/back-arrow-btn.svg" alt="back" class="w-5 h-5 brightness-0 invert">
                вернуться
            </a>
        </div>
    </div>
@stop
