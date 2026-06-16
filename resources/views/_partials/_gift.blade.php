@php
    // Безопасно декодируем или берем готовый массив data
    $giftData = is_array($gift->data) ? $gift->data : json_decode($gift->data, true);
    $type = $giftData['type'] ?? 'gift';
    $isRaffle = ($type === 'raffle');
@endphp

<div class="relative w-full rounded-[1.75rem] border {{ $isRaffle ? 'bg-[#f5f3ff] border-indigo-200/60' : 'bg-[#fff7ed] border-orange-200/60' }} shadow-xs group transition-all duration-300 mb-4 overflow-hidden">

    {{-- ВЕРХНЯЯ ЧАСТЬ: Иконка и контент --}}
    <div class="p-5 flex items-center gap-4">

        {{-- Иконка билета или подарка --}}
        <div class="w-16 h-14 rounded-2xl shrink-0 flex items-center justify-center border relative shadow-xs {{ $isRaffle ? 'bg-white border-indigo-100 text-indigo-600' : 'bg-white border-orange-100 text-orange-600' }}">
            @if($isRaffle)
                {{-- SVG Иконка Билета --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
            @else
                {{-- SVG Иконка Подарка --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0H8m12 3v10a2 2 0 01-2 2H6a2 2 0 11-2-2V10m16 0H4" />
                </svg>
            @endif
        </div>

        {{-- Описание выигранного приза --}}
        <div class="min-w-0 flex-1">
            <span class="text-[9px] font-black uppercase tracking-wider block mb-0.5 {{ $isRaffle ? 'text-indigo-600' : 'text-orange-600' }}">
                {{ $isRaffle ? '🎟️ Лотерейный билет' : '🎁 Награда от партнера' }}
            </span>
            <h3 class="text-sm font-black text-gray-900 leading-snug break-words">
                {{ $gift->name }}
            </h3>

            @if($isRaffle && isset($giftData['ticket_number']))
                <p class="text-xs font-mono text-indigo-900 mt-1 font-bold bg-white/80 border border-indigo-100 px-2 py-0.5 rounded-lg inline-block select-all">
                    Номер: {{ $giftData['ticket_number'] }}
                </p>
            @else
                <p class="text-xs text-gray-500 mt-0.5 font-medium">
                    {{ $giftData['message'] ?? 'Подарок успешно активирован и ждет вас в кабинете.' }}
                </p>
            @endif
        </div>
    </div>

    {{-- ЛИНИЯ ОТРЫВА (Перфорация купона) --}}
    <div class="relative my-0.5 z-10">
        <div class="border-t-2 border-dashed mx-6 {{ $isRaffle ? 'border-indigo-100' : 'border-orange-100' }}"></div>
        {{-- Левый и правый круглые вырезы под цвет белого бэкграунда страницы успеха --}}
        <div class="absolute -left-2 -top-2 w-4 h-4 bg-white border-r rounded-full {{ $isRaffle ? 'border-indigo-100' : 'border-orange-100' }}"></div>
        <div class="absolute -right-2 -top-2 w-4 h-4 bg-white border-l rounded-full {{ $isRaffle ? 'border-indigo-100' : 'border-orange-100' }}"></div>
    </div>

    {{-- НИЖНЯЯ ЧАСТЬ: Статус доступности --}}
    <div class="px-5 py-2.5 flex items-center justify-between text-[11px] font-bold {{ $isRaffle ? 'bg-indigo-50/30 text-indigo-500' : 'bg-orange-50/30 text-orange-500' }}">
        <span class="flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse {{ $isRaffle ? 'bg-indigo-500' : 'bg-orange-500' }}"></span>
            Статус: Доступен
        </span>

        @if($gift->valid_until)
            <span class="text-gray-400 font-medium">
                До: {{ \Carbon\Carbon::parse($gift->valid_until)->format('d.m.Y') }}
            </span>
        @endif
    </div>

</div>
