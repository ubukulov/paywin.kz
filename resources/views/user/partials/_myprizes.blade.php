@php
    // 1. Определяем, кто выдал подарок
    $isPartnerGift = (bool) $gift->share_id;

    if ($isPartnerGift) {
        // Данные партнера
        $partner = $gift->share->partner;
        $partner_profile = $partner?->partnerProfile;
        $companyName = $partner_profile->company ?? 'Партнер';
        $logoPath = $partner_profile?->logo ? asset('storage/' . $partner_profile->logo) : asset('images/my-prizes/logo.png');
    } else {
        // Данные глобальной акции Paywin
        $companyName = 'Paywin.kz';
        $logoPath = asset('images/logo_bg_white.jpg'); // Логотип самой платформы
    }

    // 2. Определяем, к чему привязан подарок (Заказ или Акция)
    $isOrderSource = $gift->source_type === \App\Models\Order::class;
    $giftType = $gift->data['type'] ?? '';
@endphp

<div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 mb-4">
    <div class="flex items-center justify-between gap-4">

        {{-- СЕКЦИЯ АВТОРА (ПАРТНЕР ИЛИ PAYWIN) --}}
        <div class="flex items-center gap-4 min-w-0">
            <div class="w-12 h-12 flex-shrink-0 bg-gray-50 rounded-2xl p-1 border border-gray-100 overflow-hidden flex items-center justify-center">
                <img src="{{ $logoPath }}"
                     class="{{ $isPartnerGift ? 'w-full h-full object-cover' : 'w-8 h-8 object-contain' }}"
                     alt="logo">
            </div>
            <div class="min-w-0">
                <h4 class="font-bold text-gray-900 leading-tight truncate">
                    {{ $companyName }}
                </h4>
                <div class="flex items-center gap-2 mt-1">
                    <i class="far fa-calendar-alt text-gray-300 text-[10px]"></i>
                    <span class="text-[11px] text-gray-400">
                        {{ $gift->created_at->format('d.m.Y') }}
                    </span>
                    @if(!$isPartnerGift)
                        <span class="bg-indigo-100 text-indigo-600 text-[9px] font-black px-1.5 py-0.5 rounded uppercase tracking-tighter">
                            Акция платформы
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- СУММА ЗАКАЗА ИЛИ МЕТКА АКЦИИ --}}
        <div class="text-right flex-shrink-0">
            @if($isOrderSource)
                <div class="text-lg font-black text-gray-900">
                    {{ number_format($gift->source->total ?? 0, 0, '.', ' ') }} ₸
                </div>
            @else
                {{-- Если это регистрация или иная акция без заказа --}}
                <div class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">
                    Special Bonus
                </div>
            @endif

            @if($gift->status === \App\Enums\UserGiftEnum::CLAIMED->value)
                <div class="inline-flex items-center gap-1 text-[10px] font-bold text-green-500 uppercase tracking-tighter">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                    получено
                </div>
            @elseif($gift->status === \App\Enums\UserGiftEnum::AVAILABLE->value)
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
            {{-- Меняем цвет иконки в зависимости от типа --}}
            <div class="w-9 h-9 {{ $isPartnerGift ? 'bg-blue-50 text-blue-600' : 'bg-indigo-50 text-indigo-600' }} rounded-xl flex items-center justify-center">
                <i class="fas {{ $giftType === 'raffle' ? 'fa-ticket-alt' : 'fa-gift' }} text-sm"></i>
            </div>
            <div>
                {{-- Заголовок типа награды --}}
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">
                    {{ ($gift->data['type'] ?? '') === 'raffle' ? 'Билет участника:' : 'Ваш выигрыш:' }}
                </p>

                <div class="text-sm text-gray-800 font-extrabold leading-tight">
                    {{-- Если есть список призов (для гарантированных подарков) --}}
                    @if(!empty($gift->data['prizes']))
                        <ul class="space-y-1">
                            @foreach($gift->data['prizes'] as $pp)
                                <li class="flex items-center gap-2">
                                    @if(count($gift->data['prizes']) > 1)
                                        <span class="w-1 h-1 bg-indigo-400 rounded-full flex-shrink-0"></span>
                                    @endif
                                    {{ $pp['name'] ?? 'Приз' }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- Если это билет (выводим номер) --}}
                    @if(isset($gift->data['ticket_number']))
                        <div class="mt-1">
                <span class="inline-block bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md border border-indigo-100 text-xs font-black">
                    #{{ $gift->data['ticket_number'] }}
                </span>
                        </div>
                    @endif

                    {{-- Если это просто название (снапшот из таблицы) --}}
                    @if(empty($gift->data['prizes']) && !isset($gift->data['ticket_number']))
                        {{ $gift->name }}
                    @endif
                </div>
            </div>
        </div>

        @if($gift->status === \App\Enums\UserGiftEnum::AVAILABLE->value && $gift->valid_until)
            <div class="text-[10px] bg-red-50 text-red-500 px-2 py-1.5 rounded-lg font-bold">
                до {{ $gift->valid_until->format('d.m') }}
            </div>
        @endif
    </div>

    {{-- КНОПКА ДЕЙСТВИЯ --}}
    @if($gift->status === \App\Enums\UserGiftEnum::AVAILABLE->value)
        <button class="w-full mt-4 {{ $isPartnerGift ? 'bg-gray-900' : 'bg-indigo-600' }} text-white py-3 rounded-2xl text-xs font-bold hover:opacity-90 transition-all">
            {{ $gift->data['type'] === 'raffle' ? 'Подробнее о розыгрыше' : 'Показать QR-код для получения' }}
        </button>
    @endif
</div>
