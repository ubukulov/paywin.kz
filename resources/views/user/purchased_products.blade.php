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
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                            <tr class="border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                <th class="pb-3 px-2">№ Заказа / Дата</th>
                                <th class="pb-3 px-2">Товар / Доставка</th>
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
