<div class="swiper-slide h-auto">
    <div class="h-full bg-white rounded-[2.5rem] border {{ $isActive ? 'border-gray-50 shadow-sm shadow-xl' : 'border-gray-100 grayscale-[0.8] opacity-80' }} transition-all duration-500 p-7 flex flex-col relative group/card">

        <div class="absolute top-6 left-8">
            <span class="text-[9px] font-black uppercase tracking-[0.15em] {{ $isActive ? 'text-green-500' : 'text-gray-400' }} flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                {{ $isActive ? 'Активна' : 'Завершена' }}
            </span>
        </div>

        <a href="{{ route('partner.my-shares.edit', ['my_share' => $share->id]) }}"
           class="absolute top-6 right-6 z-20 bg-gray-50 hover:bg-violet-600 text-gray-400 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm active:scale-95">
            изменить
        </a>

        <div class="grid grid-cols-2 gap-x-4 gap-y-6 mb-8 pt-10">
            <div class="space-y-4">
                <div class="flex flex-col">
                    <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Кол-во</span>
                    <span class="text-base font-bold text-gray-800 italic">{{ $share->count }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Остаток</span>
                    <span class="text-base font-bold italic {{ $isActive ? 'text-violet-600' : 'text-gray-500' }}">{{ $share->getRemainder() }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Клиентов</span>
                    <span class="text-base font-bold text-gray-800 italic">{{ $share->getClients() }}</span>
                </div>
            </div>

            <div class="space-y-4 border-l border-gray-100 pl-5">
                @if($share->type != 'promocode')
                <div class="flex flex-col">
                    <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Мин. заказ</span>
                    <span class="text-base font-bold text-gray-900 italic">{{ number_format($share->data['from_order'], 0, '.', ' ') }} ₸</span>
                </div>
                @endif
                <div class="flex flex-col">
                    @if($share->type !== 'promocode')
                        <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Коэф выигр.</span>
                        <span class="text-base font-bold text-orange-500 italic">{{ $share->data['c_winning'] }}%</span>
                    @else
                        <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Тип</span>
                        <span class="text-base font-bold text-blue-500 italic">Промокод</span>
                    @endif
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] text-gray-400 uppercase font-black tracking-widest mb-1">Доход</span>
                    <span class="text-base font-bold text-green-600 italic">0 ₸</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between {{ $isActive ? 'bg-violet-50/50' : 'bg-gray-50' }} rounded-[1.25rem] p-4 mb-8 transition-colors">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-white rounded-lg flex items-center justify-center shadow-sm">
                    <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                </div>
                <span class="text-sm font-black italic {{ $isActive ? 'text-violet-700' : 'text-gray-500' }}">4.6</span>
            </div>
            <button class="text-[10px] uppercase font-black tracking-widest {{ $isActive ? 'text-violet-600' : 'text-gray-400' }} hover:underline">
                отзывы
            </button>
        </div>

        <div class="mt-auto relative bg-gradient-to-br {{ $isActive ? 'from-violet-600 to-indigo-800 shadow-lg shadow-violet-100' : 'from-gray-500 to-gray-600 shadow-none' }} rounded-[1.5rem] p-6 overflow-hidden group/ticket transition-all">
            <img src="/images/mypromo/slider-card-elem.svg" alt="element" class="absolute -right-2 -bottom-2 w-16 opacity-20 transition-transform group-hover/ticket:scale-110">

            <div class="relative z-10 text-white">
                <h3 class="font-black text-lg uppercase tracking-tight leading-tight mb-1">
                    {{ \Illuminate\Support\Str::limit($share->title, 14) }}
                </h3>
                <div class="text-[10px] font-bold opacity-80 uppercase tracking-wide">
                    @if($share->type == 'promocode')
                        @if($share->promo == 'discount') Скидка {{ $share->size }}% @elseif($share->promo == 'money') Бонус {{ $share->size }} ₸ @else Подарок: {{ $share->gift_title }} @endif
                    @elseif($share->type == 'discount') Скидка {{ $share->size }}%
                    @elseif($share->type == 'cashback') Кэшбек {{ $share->size }}%
                    @else от {{ number_format($share->data['from_order'], 0, '.', ' ') }} ₸ @endif
                </div>
            </div>
        </div>
    </div>
</div>
