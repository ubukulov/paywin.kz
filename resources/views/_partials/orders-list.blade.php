<div class="bg-white rounded-3xl border border-gray-100 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-wider">
                <th class="py-4 px-4">№ Заказа / Дата</th>
                <th class="p-4">Покупатель</th>
                <th class="p-4">Товары и доставка</th>
                <th class="p-4">Приз / Подарок</th>
                <th class="p-4">Сумма</th>
                <th class="p-4 text-center">Статус заказа</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-xs font-semibold">
            @foreach($orders as $order)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    {{-- Номер заказа и дата --}}
                    <td class="p-4 align-top">
                        <div class="font-black text-gray-900 text-sm">#{{ $order->id }}</div>
                        <div class="text-[10px] text-gray-400 font-medium mt-0.5">
                            {{ $order->created_at->format('d.m.Y H:i') }}
                        </div>
                        @if($order->shipping_method)
                            <span class="inline-block mt-2 text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">
                                            🚚 {{ match($order->shipping_method) {
                                                'almaty_standard' => 'Стандарт Алматы',
                                                'almaty_express'  => 'Экспресс Алматы',
                                                'pickup'          => 'Самовывоз',
                                                'kazakhstan'      => 'По Казахстану',
                                                default           => $order->shipping_method
                                            } }}
                                        </span>
                        @endif
                    </td>

                    {{-- Данные покупателя --}}
                    <td class="p-4 align-top">
                        <span class="font-bold text-gray-900 block">{{ $order->data['name'] ?? 'Не указано' }}</span>
                        <a href="tel:{{ $order->data['phone'] ?? '' }}" class="text-indigo-600 hover:underline font-bold block mt-1 text-[11px]">
                            📞 {{ $order->data['phone'] ?? 'Нет телефона' }}
                        </a>
                        <div class="text-[10px] text-gray-400 mt-1 font-normal max-w-[180px] truncate" title="{{ $order->shipping_address }}">
                            📍 {{ $order->shipping_address }}
                        </div>
                    </td>

                    {{-- Список товаров под этого партнера --}}
                    <td class="p-4 align-top">
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                                <div class="p-2.5 rounded-xl bg-gray-50/80 border border-gray-100">
                                    <div class="flex items-center justify-between gap-2">
                                                    <span class="font-black text-gray-900 text-[11px]">
                                                        {{ $item->product_name }}
                                                    </span>
                                        <span class="bg-gray-200 text-gray-800 text-[10px] font-black px-1.5 py-0.5 rounded">
                                                        x{{ $item->quantity }}
                                                    </span>
                                    </div>

                                    {{-- Склад и тип поставки --}}
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1 text-[10px]">
                                        @if($item->is_preorder)
                                            <span class="bg-amber-100 text-amber-800 font-bold px-1.5 py-0.5 rounded">
                                                            ⏳ Предзаказ
                                                        </span>
                                        @else
                                            <span class="bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded">
                                                            ✅ В наличии
                                                        </span>
                                        @endif

                                        @if($item->warehouse)
                                            <span class="text-gray-400 font-medium">
                                                            🏭 {{ $item->warehouse->name }}
                                                        </span>
                                        @endif
                                    </div>

                                    {{-- Сроки доставки --}}
                                    <div class="mt-2 text-[10px] text-gray-500 font-medium pt-1.5 border-t border-gray-200/60 flex items-center justify-between">
                                        <span>Ожидается доставка:</span>
                                        <strong class="text-gray-900">
                                            {{ $item->estimated_delivery_at ? $item->estimated_delivery_at->format('d.m.Y') : 'Уточняется' }}
                                        </strong>
                                    </div>

                                    @if($item->delivered_at)
                                        <div class="mt-1 text-[9px] text-emerald-600 font-bold flex items-center gap-1">
                                            <span>✓ Доставлено:</span>
                                            <span>{{ $item->delivered_at->format('d.m.Y H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </td>

                    {{-- Приз --}}
                    <td class="p-4 align-top">
                        @php
                            $orderGifts = \App\Models\UserGift::where('source_type', \App\Models\Order::class)
                                ->where('source_id', $order->id)
                                ->get();
                        @endphp

                        @if($orderGifts->isNotEmpty())
                            <div class="flex flex-col gap-1">
                                @foreach($orderGifts as $gift)
                                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 font-bold text-[10px] px-2 py-1 rounded-lg border border-purple-100">
                                                    🎁 {{ $gift->name ?? ($gift->data['prizes'][0]['name'] ?? 'Приз') }}
                                                </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-400 italic text-[11px]">Без приза</span>
                        @endif
                    </td>

                    {{-- Сумма --}}
                    <td class="p-4 align-top font-black text-gray-900 text-sm whitespace-nowrap">
                        {{ number_format($order->total > 0 ? $order->total : $order->subtotal, 0, '.', ' ') }} ₸
                    </td>

                    {{-- Статус --}}
                    <td class="p-4 align-top text-center whitespace-nowrap">
                        @php
                            $statusValue = is_object($order->status) ? $order->status->value : $order->status;
                        @endphp

                        @if(in_array($statusValue, ['paid', 'PAID']))
                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-lg text-[10px] font-black border border-emerald-200">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            Оплачен
                                        </span>
                        @elseif(in_array($statusValue, ['preorder', 'PREORDER']))
                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 px-2.5 py-1 rounded-lg text-[10px] font-black border border-amber-200">
                                            ⏳ Предзаказ
                                        </span>
                        @elseif(in_array($statusValue, ['completed', 'COMPLETED']))
                            <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-[10px] font-black border border-blue-200">
                                            Завершён
                                        </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-gray-100 text-gray-600">
                                            {{ $statusValue }}
                                        </span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Пагинация --}}
@if(method_exists($orders, 'links'))
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endif
