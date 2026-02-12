@php
    $allAmount = collect($payments)->sum('amount');
    $count = $payments->count();
    $avg = $count ? round($allAmount / $count) : 0;
@endphp

{{-- STATS --}}
<div class="grid grid-cols-3 gap-3 mb-5">

    <div class="bg-white rounded-xl shadow-sm p-3 text-center">
        <div class="text-lg font-semibold text-gray-900">{{ $allAmount }} ₸</div>
        <div class="text-xs text-gray-400">Всего</div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-3 text-center">
        <div class="text-lg font-semibold text-gray-900">{{ $count }}</div>
        <div class="text-xs text-gray-400">Кол-во</div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-3 text-center">
        <div class="text-lg font-semibold text-gray-900">{{ $avg }} ₸</div>
        <div class="text-xs text-gray-400">Ср. сумма</div>
    </div>

</div>



{{-- PURCHASE LIST --}}
<div class="space-y-3">

    @foreach($payments as $payment)

        @php
            $partner_profile = $payment->partner?->profile;
        @endphp

        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-3">

            {{-- logo --}}
            <img
                src="{{ $partner_profile?->logo ?? '/images/history/logo.png' }}"
                class="w-10 h-10 rounded-lg object-cover border"
                alt="logo"
            >

            {{-- info --}}
            <div class="flex-1">

                <div class="font-medium text-sm">
                    {{ $partner_profile->company ?? '-' }}
                </div>

                <div class="text-xs text-gray-400">
                    {{ $payment->created_at->format('d.m.Y H:i') }}
                </div>

            </div>

            {{-- status --}}
            <div class="text-xs text-gray-500">
                покупка
            </div>

            {{-- sum --}}
            <div class="font-semibold text-gray-900">
                {{ $payment->amount }} ₸
            </div>

        </div>

    @endforeach

</div>

