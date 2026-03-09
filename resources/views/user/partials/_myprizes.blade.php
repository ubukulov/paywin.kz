@php
    // Предполагаем, что связь в модели Share называется partner (тип User)
    $partner = $gift->share->partner;
    $partner_profile = $partner?->partnerProfile;
@endphp

<div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 mb-4">
    <div class="flex items-center justify-between gap-4">

        {{-- СЕКЦИЯ ПАРТНЕРА --}}
        <div class="flex items-center gap-4 min-w-0">
            <div class="w-12 h-12 flex-shrink-0 bg-gray-50 rounded-2xl p-1 border border-gray-100 overflow-hidden">
                <img src="{{ $partner_profile?->logo ? asset('storage/' . $partner_profile->logo) : asset('images/my-prizes/logo.png') }}"
                     class="w-full h-full object-cover"
                     alt="logo">
            </div>
            <div class="min-w-0">
                <h4 class="font-bold text-gray-900 leading-tight truncate">
                    {{ $partner_profile->company ?? 'Партнер' }}
                </h4>
                <div class="flex items-center gap-2 mt-1">
                    <i class="far fa-calendar-alt text-gray-300 text-[10px]"></i>
                    <span class="text-[11px] text-gray-400">
                        {{ $gift->created_at->format('d.m.Y') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- СУММА ЗАКАЗА И СТАТУС --}}
        <div class="text-right flex-shrink-0">
            {{-- Сумма заказа из полиморфной связи source (Order) --}}
            <div class="text-lg font-black text-gray-900">
                {{ number_format($gift->source->total ?? 0, 0, '.', ' ') }} ₸
            </div>

            @if($gift->status === 'claimed')
                <div class="inline-flex items-center gap-1 text-[10px] font-bold text-green-500 uppercase tracking-tighter">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                    получено
                </div>
            @elseif($gift->status === 'available')
                <div class="inline-flex items-center gap-1 text-[10px] font-bold text-blue-500 uppercase tracking-tighter">
                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                    готов к выдаче
                </div>
            @else
                <div class="inline-flex items-center gap-1 text-[10px] font-bold text-red-400 uppercase tracking-tighter">
                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span>
                    истек
                </div>
            @endif
        </div>
    </div>

    <div class="my-4 border-t border-dashed border-gray-100"></div>

    {{-- ОПИСАНИЕ ПРИЗА --}}
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                <i class="fas fa-gift text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Ваш выигрыш:</p>
                <p class="text-sm text-gray-800 font-extrabold leading-tight">
                    {{ $gift->name }} {{-- Используем снапшот имени из таблицы user_gifts --}}
                </p>
            </div>
        </div>

        {{-- Срок действия, если подарок еще не получен --}}
        @if($gift->status === 'available' && $gift->valid_until)
            <div class="text-[10px] bg-red-50 text-red-500 px-2 py-1.5 rounded-lg font-bold">
                до {{ $gift->valid_until->format('d.m') }}
            </div>
        @endif
    </div>

    {{-- КНОПКА ПОКАЗАТЬ КОД (если доступен) --}}
    @if($gift->status === 'available')
        <button class="w-full mt-4 bg-gray-900 text-white py-3 rounded-2xl text-xs font-bold hover:bg-black transition-colors">
            Показать QR-код для получения
        </button>
    @endif
</div>
