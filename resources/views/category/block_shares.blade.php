{{-- Блок призов (Слайдер: акцент на изображении) --}}
@if($gifts && $gifts->isNotEmpty())
    <div class="mt-4">
        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] mb-4 flex items-center gap-2">
            <i class="fas fa-gift text-indigo-500"></i>
            Суперпризы при покупке
        </h4>

        {{-- Контейнер с жесткой фиксацией слайдов --}}
        <div class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide select-none gap-2">
            @foreach($gifts as $share)
                {{-- Один слайд на весь экран --}}
                <div class="snap-center shrink-0 w-full">
                    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-2 shadow-sm relative group overflow-hidden">

                        {{-- Огромное изображение приза --}}
                        <div class="w-full h-44 bg-gray-50 rounded-[2rem] overflow-hidden relative">
                            <img
                                src="{{ (isset($share->data['image']) && $share->data['image']) ? asset('storage/' . $share->data['image']) : asset('images/no-image.png') }}"
                                alt="{{ $share->title }}"
                                class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-700"
                            >

                            {{-- Бейдж шанса поверх картинки для эффекта --}}
                            <div class="absolute bottom-4 left-4">
                                <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-tighter border border-white/20">
                                    {{ $share->c_winning == 1 ? 'Гарантированно' : 'Шанс 1 к ' . $share->c_winning }}
                                </span>
                            </div>
                        </div>

                        {{-- Название приза (компактно снизу) --}}
                        <div class="p-4 text-center">
                            <h3 class="text-base font-extrabold text-gray-900 leading-tight line-clamp-1">
                                {{ $share->title }}
                            </h3>
                        </div>

                        {{-- Кнопка инфо --}}
                        <div class="absolute top-6 right-6">
                            <div class="w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-gray-400 shadow-sm border border-gray-100">
                                <i class="fas fa-info text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Индикаторы (точки) под слайдером --}}
        @if($gifts->count() > 1)
            <div class="flex justify-center gap-1.5 mt-4">
                @foreach($gifts as $index => $share)
                    <div class="h-1 rounded-full transition-all duration-300 {{ $loop->first ? 'w-4 bg-indigo-500' : 'w-1 bg-gray-200' }}"></div>
                @endforeach
            </div>
        @endif
    </div>
@endif
