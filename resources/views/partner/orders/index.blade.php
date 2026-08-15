@extends('partner.partner')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight">🛍️ Заказы ваших товаров</h1>
                <p class="text-xs text-gray-400 mt-1">Список оплаченных покупок и предзаказов ваших продуктов клиентами Paywin.</p>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-xs">
                <div class="text-4xl mb-3">📦</div>
                <h3 class="text-sm font-bold text-gray-900">Заказов пока нет</h3>
                <p class="text-xs text-gray-400 max-w-sm mx-auto mt-1">Как только клиенты купят ваши товары, они сразу появятся здесь.</p>
            </div>
        @else
            @include('_partials.orders-list', compact('orders'))
        @endif
    </div>
@endsection
