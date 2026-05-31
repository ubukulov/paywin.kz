{{-- БЛОК АКЦИЙ ОТ ПЛАТФОРМЫ PAYWIN.KZ (Слайдер в стиле Купона/Билета) --}}
@if(isset($platformPromotions) && $platformPromotions->isNotEmpty())
    <div class="mt-5 p-5 bg-[#f5f3ff] border border-indigo-200/60 rounded-[2.25rem] shadow-sm relative group/main_pw">

        {{-- Крупный заголовок блока --}}
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-indigo-100">
            <div class="flex items-center gap-2.5">
                {{-- Встроенный логотип-PW --}}
                <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-md shadow-indigo-200/50 flex-shrink-0 text-xs font-black">
                    PW
                </div>
                <h4 class="text-sm font-black text-indigo-900 uppercase tracking-wider">
                    Бонус от Paywin.kz
                </h4>
            </div>

            <span class="text-[10px] font-black text-indigo-600 bg-white border border-indigo-200 px-3 py-1.5 rounded-xl shadow-xs shrink-0 uppercase tracking-wider">
                Акция платформы
            </span>
        </div>

        {{-- Оболочка слайдера --}}
        <div class="w-full max-w-full overflow-hidden relative rounded-[1.75rem]">

            @if($platformPromotions->count() > 1)
                <button type="button" onclick="pwSliderPrev()" class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/90 rounded-full shadow-md flex items-center justify-center text-indigo-600 font-bold opacity-0 group-hover/main_pw:opacity-100 transition-opacity duration-300 hover:bg-indigo-50 select-none">
                    ‹
                </button>
                <button type="button" onclick="pwSliderNext()" class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/90 rounded-full shadow-md flex items-center justify-center text-indigo-600 font-bold opacity-0 group-hover/main_pw:opacity-100 transition-opacity duration-300 hover:bg-indigo-50 select-none">
                    ›
                </button>
            @endif

            <div id="pw-slider" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none-custom select-none w-full scroll-smooth">
                @foreach($platformPromotions as $promo)
                    <div class="snap-center shrink-0 w-full px-0.5">

                        {{-- Карточка-Билет --}}
                        <div class="bg-white rounded-[1.75rem] border border-indigo-100/80 relative group transition-all duration-300 shadow-xs">

                            {{-- ВЕРХНИЙ БЛОК: Иконка слева, Описание справа --}}
                            <div class="p-4 pb-3 flex items-center gap-4">

                                {{-- Иконка награды слева (ЗАМЕНЕНО НА ЧИСТЫЙ SVG) --}}
                                <div class="w-24 h-20 rounded-xl bg-indigo-50 border border-indigo-100/50 shrink-0 flex items-center justify-center text-indigo-600 relative">
                                    @if($promo->reward_type === 'raffle')
                                        {{-- SVG Иконка Билета --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                    @else
                                        {{-- SVG Иконка Подарка --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0H8m12 3v10a2 2 0 01-2 2H6a2 2 0 11-2-2V10m16 0H4" />
                                        </svg>
                                    @endif
                                </div>

                                {{-- Описание акции справа --}}
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-black text-gray-900 leading-snug break-words line-clamp-1">
                                        {{ $promo->title }}
                                    </h3>
                                    <p class="text-xs text-indigo-700/90 mt-1 font-medium line-clamp-2 leading-tight">
                                        @if($promo->reward_type === 'raffle')
                                            После оплаты заказа вы автоматически получите <span class="font-bold underline text-indigo-900">Билет участника</span>.
                                        @else
                                            Приз: <span class="font-bold text-indigo-900">{{ collect($promo->prizes)->pluck('name')->implode(', ') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            {{-- ГОРИЗОНТАЛЬНАЯ ЛИНИЯ ОТРЫВА (Перфорация купона) --}}
                            <div class="relative my-0.5 z-10">
                                <div class="border-t-2 border-dashed border-indigo-100 mx-5"></div>
                                {{-- Боковые вырезы под фон блока (#f5f3ff) --}}
                                <div class="absolute -left-2 -top-2 w-4 h-4 bg-[#f5f3ff] border-r border-indigo-100 rounded-full"></div>
                                <div class="absolute -right-2 -top-2 w-4 h-4 bg-[#f5f3ff] border-l border-indigo-100 rounded-full"></div>
                            </div>

                            {{-- НИЖНИЙ БЛОК: Тип и статус строго снизу --}}
                            <div class="px-4 py-3 bg-indigo-50/20 rounded-b-[1.75rem] flex justify-center">
                                <span class="bg-indigo-50 border border-indigo-200 text-indigo-600 text-xs font-black px-4 py-1.5 rounded-xl block w-full text-center uppercase tracking-wide">
                                    {{ $promo->reward_type === 'raffle' ? '🎟️ Розыгрыш суперприза' : '🎁 Гарантированная награда' }}
                                </span>
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        {{-- Пагинация (точки) --}}
        @if($platformPromotions->count() > 1)
            <div class="flex justify-center gap-1.5 mt-4">
                @foreach($platformPromotions as $index => $promo)
                    <div data-pw-index="{{ $index }}" class="pw-dot h-1 rounded-full transition-all duration-300 {{ $loop->first ? 'w-4 bg-indigo-600' : 'w-1 bg-gray-300' }}"></div>
                @endforeach
            </div>
        @endif

    </div>
@endif

<script>
    function pwSliderNext() {
        const slider = document.getElementById('pw-slider');
        if (slider) {
            if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 5) {
                slider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: slider.clientWidth, behavior: 'smooth' });
            }
        }
    }

    function pwSliderPrev() {
        const slider = document.getElementById('pw-slider');
        if (slider) {
            if (slider.scrollLeft <= 5) {
                slider.scrollTo({ left: slider.scrollWidth, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: -slider.clientWidth, behavior: 'smooth' });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('pw-slider');
        if (slider) {
            slider.addEventListener('scroll', () => {
                const index = Math.round(slider.scrollLeft / slider.clientWidth);
                document.querySelectorAll('.pw-dot').forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.add('w-4', 'bg-indigo-600');
                        dot.classList.remove('w-1', 'bg-gray-300');
                    } else {
                        dot.classList.remove('w-4', 'bg-indigo-600');
                        dot.classList.add('w-1', 'bg-gray-300');
                    }
                });
            });
        }
    });
</script>
