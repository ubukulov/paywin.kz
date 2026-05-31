@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-12">

        {{-- Главный блок успеха --}}
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-green-100 text-2xl shadow-xs">
                ✅
            </div>
            <h1 class="text-3xl font-black mb-2 text-gray-900">Спасибо за заказ!</h1>
            <p class="text-gray-500 text-sm max-w-md mx-auto">
                Ваш платеж успешно прошёл через TipTopPay, и заказ уже принят в обработку партнерами.
            </p>
        </div>

        {{-- СЕКЦИЯ ВЫИГРАННЫХ ПРИЗОВ PAYWIN (Геймификация) --}}
        @if(isset($gifts) && $gifts->isNotEmpty())
            <div class="mb-10">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xl">🎁</span>
                    <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight">
                        Вы выиграли призы!
                    </h2>
                </div>

                <p class="text-xs text-gray-400 mb-4 -mt-3">
                    Они уже добавлены в ваш личный кабинет. Покажите QR-код партнеру для получения.
                </p>

                {{-- Цикл по сгенерированным подаркам --}}
                <div class="space-y-1">
                    @foreach($gifts as $gift)
                        {{-- Подключаем твой адаптированный партиал подарка --}}
                        @include('_partials._gift', ['gift' => $gift])
                    @endforeach
                </div>
            </div>
        @else
            {{-- Фолбек на случай, если под условия акций товар не подошел --}}
            <div class="mb-8 p-4 rounded-2xl bg-gray-50 border border-gray-100 text-center text-xs text-gray-400 font-medium">
                В этот раз призы по акциям партнеров не были начислены. Покупайте больше для участия в розыгрышах!
            </div>
        @endif

        {{-- Кнопки навигации --}}
        <div class="flex flex-col sm:flex-row items-center gap-3 justify-center pt-4 border-t border-gray-100">
            <a href="/"
               class="w-full sm:w-auto text-center bg-orange-500 hover:bg-orange-600 text-white py-3.5 px-8 rounded-2xl text-sm font-black transition duration-200 shadow-sm">
                На главную витрину
            </a>
            <a href="/profile/prizes"
               class="w-full sm:w-auto text-center bg-gray-100 hover:bg-gray-200 text-gray-800 py-3.5 px-8 rounded-2xl text-sm font-bold transition duration-200">
                Мои призы в кабинете
            </a>
        </div>

    </div>
@endsection
