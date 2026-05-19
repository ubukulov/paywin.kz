{{-- Блок суперпризов (Стиль: Картинка и имя в ряд, Счетчик строго внизу) --}}
@if($gifts && $gifts->isNotEmpty())
    <div class="mt-5 p-5 bg-[#fff7ed] border border-orange-200/60 rounded-[2.25rem] shadow-sm relative group/main">

        {{-- Крупный заголовок блока --}}
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-orange-100">
            <div class="flex items-center gap-2.5">
                {{-- Встроенный SVG Подарок --}}
                <div class="w-9 h-9 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-orange-200/50 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0H8m12 3v10a2 2 0 01-2 2H6a2 2 0 11-2-2V10m16 0H4" />
                    </svg>
                </div>
                <h4 class="text-sm font-black text-orange-900 uppercase tracking-wider">
                    Рандомный бонус
                </h4>
            </div>

            <span class="text-xs font-black text-orange-600 bg-white border border-orange-200 px-3 py-1.5 rounded-xl shadow-xs shrink-0">
                при покупке от {{ number_format($gifts->first()->from_order ?? 4000, 0, '.', ' ') }} ₸
            </span>
        </div>

        {{-- Оболочка слайдера --}}
        <div class="w-full max-w-full overflow-hidden relative rounded-[1.75rem]">

            @if($gifts->count() > 1)
                <button type="button" onclick="giftSliderPrev()" class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/90 rounded-full shadow-md flex items-center justify-center text-orange-600 font-bold opacity-0 group-hover/main:opacity-100 transition-opacity duration-300 hover:bg-orange-50 select-none">
                    ‹
                </button>
                <button type="button" onclick="giftSliderNext()" class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-8 h-8 bg-white/90 rounded-full shadow-md flex items-center justify-center text-orange-600 font-bold opacity-0 group-hover/main:opacity-100 transition-opacity duration-300 hover:bg-orange-50 select-none">
                    ›
                </button>
            @endif

            <div id="gifts-slider" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-none-custom select-none w-full scroll-smooth">
                @foreach($gifts as $share)
                    <div class="snap-center shrink-0 w-full px-0.5">

                        {{-- Карточка-Билет --}}
                        <div class="bg-white rounded-[1.75rem] border border-orange-100/80 relative group transition-all duration-300 shadow-xs">

                            {{-- ВЕРХНИЙ БЛОК: Картинка слева, Наименование справа --}}
                            <div class="p-4 pb-3 flex items-center gap-4">
                                {{-- Картинка слева --}}
                                <div class="w-24 h-20 overflow-hidden rounded-xl bg-gray-50 shrink-0 relative">
                                    <img
                                        src="{{ (isset($share->data['image']) && $share->data['image']) ? asset('storage/' . $share->data['image']) : asset('images/no-image.png') }}"
                                        alt="{{ $share->title }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >
                                </div>

                                {{-- Наименование подарка справа --}}
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-base font-black text-gray-900 leading-snug break-words line-clamp-2">
                                        {{ $share->title }}
                                    </h3>
                                </div>
                            </div>

                            {{-- ГОРИЗОНТАЛЬНАЯ ЛИНИЯ ОТРЫВА (Перфорация купона) --}}
                            <div class="relative my-0.5 z-10">
                                <div class="border-t-2 border-dashed border-orange-100 mx-5"></div>
                                {{-- Боковые вырезы под фон блока (#fff7ed) --}}
                                <div class="absolute -left-2 -top-2 w-4 h-4 bg-[#fff7ed] border-r border-orange-100 rounded-full"></div>
                                <div class="absolute -right-2 -top-2 w-4 h-4 bg-[#fff7ed] border-l border-orange-100 rounded-full"></div>
                            </div>

                            {{-- НИЖНИЙ БЛОК: Количество строго снизу --}}
                            <div class="px-4 py-3 bg-orange-50/20 rounded-b-[1.75rem] flex justify-center">
                                <span class="bg-orange-50 border border-orange-200 text-orange-600 text-xs font-black px-4 py-1.5 rounded-xl block w-full text-center">
                                    Осталось: {{ $share->count ?? 0 }} шт
                                </span>
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        @if($gifts->count() > 1)
            <div class="flex justify-center gap-1.5 mt-4">
                @foreach($gifts as $index => $share)
                    <div data-slide-index="{{ $index }}" class="gift-dot h-1 rounded-full transition-all duration-300 {{ $loop->first ? 'w-4 bg-orange-500' : 'w-1 bg-gray-300' }}"></div>
                @endforeach
            </div>
        @endif

    </div>
@endif

<style>
    .scrollbar-none-custom::-webkit-scrollbar { display: none; }
    .scrollbar-none-custom { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    function giftSliderNext() {
        const slider = document.getElementById('gifts-slider');
        if (slider) {
            if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 5) {
                slider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: slider.clientWidth, behavior: 'smooth' });
            }
        }
    }

    function giftSliderPrev() {
        const slider = document.getElementById('gifts-slider');
        if (slider) {
            if (slider.scrollLeft <= 5) {
                slider.scrollTo({ left: slider.scrollWidth, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: -slider.clientWidth, behavior: 'smooth' });
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const slider = document.getElementById('gifts-slider');
        if (slider) {
            slider.addEventListener('scroll', () => {
                const index = Math.round(slider.scrollLeft / slider.clientWidth);
                document.querySelectorAll('.gift-dot').forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.add('w-4', 'bg-orange-500');
                        dot.classList.remove('w-1', 'bg-gray-300');
                    } else {
                        dot.classList.remove('w-4', 'bg-orange-500');
                        dot.classList.add('w-1', 'bg-gray-300');
                    }
                });
            });
        }
    });
</script>
