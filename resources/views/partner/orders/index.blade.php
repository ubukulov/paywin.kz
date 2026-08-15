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
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-wider">
                        <th class="pb-3 px-2">№ Заказа / Дата</th>
                        <th class="p-4">Покупатель</th>
                        <th class="pb-3 px-2">Товар / доставка</th>
                        <th class="pb-3 px-2">Приз</th>
                        <th class="pb-3 px-2">Сумма покупки</th>
                        <th class="pb-3 px-2 text-center">Статус</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-xs font-semibold">
                    @foreach($orders as $order)
                        @php
                            // 1. Ищем запись реферала для этого пользователя и агента
                            $referral = \App\Models\Referral::where('agent_id', auth()->id())
                                ->where('user_id', $order->user_id)
                                ->first();

                            // 2. Рассчитываем вознаграждение через метод getEarn() модели Referral (если запись найдена)
                            $reward = $referral ? $referral->getReferralEarnInOrder($order) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            {{-- Номер заказа и дата --}}
                            <td class="py-4 px-2">
                                <div class="font-black text-gray-900">#{{ $order->id }}</div>
                                <div class="text-[10px] text-gray-400 font-medium">{{ $order->created_at->format('d.m.Y H:i') }}</div>
                            </td>

                            {{-- Данные покупателя --}}
                            <td class="p-4">
                                <span class="font-bold text-gray-900 block">{{ $order->data['name'] ?? 'Не указано' }}</span>
                                <a href="tel:{{ $order->data['phone'] ?? '' }}" class="text-indigo-600 hover:underline font-medium block mt-0.5">
                                    {{ $order->data['phone'] ?? 'Нет телефона' }}
                                </a>
                            </td>

                            {{-- Товар --}}
                            <td class="py-4 px-2">
                                @foreach($order->items as $orderItem)
                                    <div class="text-[10px] text-gray-400 font-medium">{{ $orderItem->product_name }} | {{ $orderItem->estimated_delivery_at->format('d.m.Y H:i') }}</div>
                                @endforeach
                            </td>

                            {{-- Приз --}}
                            <td class="py-4 px-2">
                                @php
                                    // Ищем подарки, привязанные к заказу (Order)
                                    $orderGifts = \App\Models\UserGift::where('source_type', \App\Models\Order::class)
                                        ->where('source_id', $order->id)
                                        ->get();
                                @endphp

                                @if($orderGifts->isNotEmpty())
                                    <div class="flex flex-col gap-1 mt-1">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($orderGifts as $gift)
                                                <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 font-bold text-[10px] px-2 py-1 rounded-lg border border-purple-100">
                                                            🎁 {{ $gift->name ?? ($gift->data['prizes'][0]['name'] ?? 'Приз') }}
                                                        </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-[11px]">Без приза</span>
                                @endif
                            </td>

                            {{-- Сумма заказа --}}
                            <td class="py-4 px-2 font-black text-gray-900">
                                @if($order->total == 0)
                                    {{ number_format($order->subtotal, 0, '.', ' ') }} ₸
                                @else
                                    {{ number_format($order->total, 0, '.', ' ') }} ₸
                                @endif
                            </td>

                            {{-- Статус заказа --}}
                            <td class="py-4 px-2 text-center">
                                @php
                                    $statusValue = is_object($order->status) ? $order->status->value : $order->status;
                                @endphp
                                @if(in_array($statusValue, ['paid', 'completed', 'PAID', 'COMPLETED']))
                                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-[10px] font-black border border-green-200">
                                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                                        {{ $statusValue === 'completed' || $statusValue === 'COMPLETED' ? 'Выполнен' : 'Оплачен' }}
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

            {{-- Пагинация --}}
            @if(method_exists($orders, 'links'))
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
