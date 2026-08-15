@extends('user.user')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">

        {{-- Заголовок страницы --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                    <span>📦</span> Мои заказы
                </h1>
                <p class="text-xs text-gray-400 mt-1">
                    История ваших оплаченных покупок и предзаказов на платформе.
                </p>
            </div>
            <a href="/" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-xl transition">
                Назад на витрину →
            </a>
        </div>

        @if($purchasedItems->isEmpty())
            {{-- Фолбек, если покупок еще нет --}}
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-xs">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    🛍️
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">Вы еще ничего не купили</h3>
                <p class="text-xs text-gray-400 max-w-xs mx-auto mb-6">
                    Все оплаченные вами товары от партнеров будут мгновенно отображаться на этой странице.
                </p>
                <a href="/" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-3 px-6 rounded-2xl transition shadow-sm">
                    Перейти к покупкам
                </a>
            </div>
        @else
            <div class="bg-white rounded-3xl p-4 sm:p-6 shadow-sm border border-gray-100">

                @if(isset($orders) && $orders->count() > 0)
                    @include('_partials.orders-list', compact('orders'))
                @else
                    {{-- Пустое состояние --}}
                    <div class="text-center py-12">
                        <div class="text-4xl mb-3">🛒</div>
                        <h4 class="text-base font-bold text-gray-800">Покупок пока нет</h4>
                        <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                            Делись своими промокодами. Как только рефералы сделают покупки, они сразу отобразятся в этом списке!
                        </p>
                    </div>
                @endif

            </div>
        @endif

    </div>
@endsection
