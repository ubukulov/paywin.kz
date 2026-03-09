{{-- РАСЧЕТ СТАТИСТИКИ --}}
@php
    // Считаем только оплаченные заказы для статистики
    $paidOrders = $orders->where('status', 'paid');
    $allAmount = $paidOrders->sum('total');
    $count = $paidOrders->count();
    $avg = $count ? round($allAmount / $count) : 0;
@endphp

{{-- STATS --}}
<div class="grid grid-cols-3 gap-3 mb-6">
    <div class="bg-white rounded-2xl shadow-sm p-3 text-center border border-gray-50">
        <div class="text-base font-bold text-blue-600">{{ number_format($allAmount, 0, '.', ' ') }} ₸</div>
        <div class="text-[10px] text-gray-400 uppercase font-medium">Потрачено</div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-3 text-center border border-gray-50">
        <div class="text-base font-bold text-gray-900">{{ $count }}</div>
        <div class="text-[10px] text-gray-400 uppercase font-medium">Заказов</div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-3 text-center border border-gray-50">
        <div class="text-base font-bold text-gray-900">{{ number_format($avg, 0, '.', ' ') }} ₸</div>
        <div class="text-[10px] text-gray-400 uppercase font-medium">Ср. чек</div>
    </div>
</div>

{{-- PURCHASE LIST --}}
<div class="space-y-3">
    @forelse($orders as $order)
        @php
            // Получаем профиль партнера через связь, которую мы заложили в модель Order
            $partner = $order->partner?->partnerProfile;
        @endphp

        <div class="bg-white rounded-2xl shadow-sm p-4 flex items-center gap-3 border border-gray-50 active:bg-gray-50 transition">

            {{-- Logo --}}
            <div class="relative">
                <img
                    src="{{ $partner?->logo ? asset('storage/'.$partner->logo) : asset('images/no-logo.png') }}"
                    class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm"
                    alt="logo"
                >
                {{-- Маленький индикатор статуса на логотипе --}}
                <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full border-2 border-white
                    {{ $order->status == 'paid' ? 'bg-green-500' : 'bg-yellow-500' }}">
                </div>
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm text-gray-800 truncate">
                    {{ $partner->company ?? 'Партнер #' . $order->partner_id }}
                </div>
                <div class="text-[10px] text-gray-400 flex items-center gap-1">
                    <i class="far fa-clock"></i>
                    {{ $order->created_at->format('d.m.Y H:i') }}
                </div>
            </div>

            {{-- Price & Status --}}
            <div class="text-right">
                <div class="font-extrabold text-gray-900 whitespace-nowrap">
                    {{ number_format($order->total, 0, '.', ' ') }} ₸
                </div>
                <div class="text-[9px] font-bold uppercase tracking-tighter
                    {{ $order->status == 'paid' ? 'text-green-500' : 'text-yellow-500' }}">
                    {{ $order->status == 'paid' ? 'Оплачено' : 'В обработке' }}
                </div>
            </div>

        </div>
    @empty
        <div class="text-center py-12">
            <div class="text-gray-300 mb-2">
                <i class="fas fa-shopping-bag fa-3x"></i>
            </div>
            <p class="text-gray-400 text-sm">У вас еще нет покупок</p>
            <a href="{{ route('home') }}" class="text-blue-600 text-xs font-bold mt-2 inline-block">Перейти к покупкам</a>
        </div>
    @endforelse

    {{-- Пагинация --}}
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
