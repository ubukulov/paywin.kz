@extends('partner.partner')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="mb-6">
            <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight">🛍️ Заказы ваших товаров</h1>
            <p class="text-xs text-gray-400 mt-1">Список оплаченных покупок и предзаказов ваших продуктов клиентами Paywin.</p>
        </div>

        @if($partnerOrderItems->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-xs">
                <div class="text-4xl mb-3">📦</div>
                <h3 class="text-sm font-bold text-gray-900">Заказов пока нет</h3>
                <p class="text-xs text-gray-400 max-w-sm mx-auto mt-1">Как только клиенты купят ваши товары, они сразу появятся здесь.</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-gray-400 uppercase font-black text-[10px] tracking-wider">
                            <th class="p-4">Заказ / Дата</th>
                            <th class="p-4">Покупатель</th>
                            <th class="p-4">Товар</th>
                            <th class="p-4">Кол-во / Сумма</th>
                            <th class="p-4">Адрес доставки</th>
                            <th class="p-4">Выигранный приз</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-xs">
                        @foreach($partnerOrderItems as $item)
                            @php
                                $order = $item->order;
                                $buyer = $order->user;
                                $isPreorder = $order->status === \App\Enums\OrderEnum::PREORDER->value;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                {{-- Номер заказа и Дата --}}
                                <td class="p-4 whitespace-nowrap">
                                    <span class="font-bold text-gray-900 block">№{{ $order->id }}</span>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">{{ $item->created_at->format('d.m.Y H:i') }}</span>

                                    @if($isPreorder)
                                        <span class="inline-block text-[9px] font-bold bg-amber-50 text-amber-700 px-1.5 py-0.5 rounded-md mt-1 border border-amber-100">Предзаказ</span>
                                    @else
                                        <span class="inline-block text-[9px] font-bold bg-green-50 text-green-700 px-1.5 py-0.5 rounded-md mt-1 border border-green-100">Оплачен</span>
                                    @endif
                                </td>

                                {{-- Данные покупателя --}}
                                <td class="p-4">
                                    <span class="font-bold text-gray-900 block">{{ $buyer->name ?? 'Не указано' }}</span>
                                    <a href="tel:{{ $buyer->phone ?? '' }}" class="text-indigo-600 hover:underline font-medium block mt-0.5">
                                        {{ $buyer->phone ?? 'Нет телефона' }}
                                    </a>
                                </td>

                                {{-- Товар --}}
                                <td class="p-4 max-w-xs">
                                    <span class="font-bold text-gray-900 block truncate" title="{{ $item->product->name ?? 'Удален' }}">
                                        {{ $item->product->name ?? 'Товар удален' }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 block mt-0.5">ID товара: {{ $item->product_id }}</span>
                                </td>

                                {{-- Кол-во и Сумма --}}
                                <td class="p-4 whitespace-nowrap">
                                    <span class="text-gray-900 font-medium block">{{ $item->quantity }} шт.</span>
                                    <span class="font-bold text-gray-900 block mt-0.5">{{ number_format($item->total, 0, '.', ' ') }} ₸</span>
                                </td>

                                {{-- Адрес доставки --}}
                                <td class="p-4 max-w-xs">
                                    <p class="text-gray-600 break-words line-clamp-2" title="{{ $order->shipping_address }}">
                                        {{ $order->shipping_address ?? 'Самовывоз / Не указан' }}
                                    </p>
                                </td>

                                {{-- Выигранный приз --}}
                                <td class="p-4">
                                    @if($item->gifts && $item->gifts->count() > 0)
                                        <div class="flex flex-col gap-1">
                                            @foreach($item->gifts as $gift)
                                                <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 font-bold text-[10px] px-2 py-1 rounded-lg border border-purple-100 max-w-max">
                                                    🎁 {{ $gift->title ?? $gift->name ?? 'Приз' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic text-[11px]">Без приза</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Пагинация --}}
            <div class="mt-4">
                {{ $partnerOrderItems->links() }}
            </div>
        @endif
    </div>
@endsection
