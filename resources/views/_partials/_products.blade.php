@foreach($products as $product)
    <a href="{{ route('product.show', ['slug' => $product->slug]) }}" class="group">
        <article class="bg-white rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-200 h-full flex flex-col">

            {{-- Изображение товара --}}
            <div class="relative bg-gray-50 aspect-square w-full overflow-hidden">
                <img src="{{ $product->mainImage->url }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-contain p-2 group-hover:scale-102 transition-transform duration-200"
                     loading="lazy" />

                <span class="absolute top-2 left-2 bg-orange-500 text-white text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg shadow-sm">
                    Новинка
                </span>

                <button type="button" aria-label="Добавить в избранное"
                        class="absolute top-2 right-2 bg-white/90 backdrop-blur-xs p-2 rounded-full shadow-xs hover:bg-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.343 3.172 10.83a4 4 0 010-5.657z" />
                    </svg>
                </button>
            </div>

            {{-- Контентная часть карточки --}}
            <div class="p-3 sm:p-4 flex flex-col flex-1 justify-between gap-3">

                <div class="flex flex-col flex-1 justify-between">
                    <div>
                        {{-- Название товара --}}
                        <h3 class="text-xs sm:text-sm font-medium text-gray-800 leading-snug break-words line-clamp-2 min-h-[2.5rem]">
                            {{ $product->name }}
                        </h3>

                        {{-- Цена в стиле маркетплейса --}}
                        <div class="flex items-baseline gap-1 mt-2">
                            <span class="text-base sm:text-lg font-black text-gray-900">
                                {{ number_format($product->price, 0, '.', ' ') }}
                            </span>
                            <span class="text-xs font-bold text-gray-500">₸</span>
                        </div>

                        {{-- РЕЙТИНГ И ОТЗЫВЫ (В стиле Kaspi) --}}
                        {{-- Предполагается, что в модели Product есть связи или поля для среднего рейтинга и количества одобренных отзывов --}}
                        @php
                            $rating = round($product->approved_reviews_avg_rating ?? $product->reviews_avg_rating ?? 0, 1);
                            $reviewsCount = $product->approved_reviews_count ?? $product->reviews_count ?? 0;
                        @endphp

                        {{--<div class="flex items-center gap-1 mt-1.5 min-h-[1.25rem]">
                            @if($reviewsCount > 0)
                                --}}{{-- Иконка желтой звезды --}}{{--
                                <div class="flex items-center text-amber-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                                --}}{{-- Оценка числом --}}{{--
                                <span class="text-xs font-black text-gray-800">{{ $rating }}</span>
                                --}}{{-- Количество отзывов в скобках --}}{{--
                                <span class="text-xs text-gray-400">({{ $reviewsCount }} {{ trans_choice('отзыв|отзыва|отзывов', $reviewsCount) }})</span>
                            @else
                                --}}{{-- Фолбек, если отзывов еще нет (чтобы сетка не смещалась по высоте) --}}{{--
                                <span class="text-[11px] text-gray-400 font-medium">Нет отзывов</span>
                            @endif
                        </div>--}}

                        <div>
                            <span class="text-[11px] text-gray-400 font-medium">Купили: {{ rand(10,40) }}</span>
                        </div>
                    </div>

                    {{-- Кнопка «Подробнее» --}}
                    <div class="mt-3">
                        <div class="w-full text-center py-2 bg-orange-500 group-hover:bg-orange-600 text-white rounded-xl text-xs sm:text-sm font-bold transition duration-200 shadow-sm">
                            Подробнее
                        </div>
                    </div>
                </div>

                {{-- Геймификация Paywin (Нижний брендированный блок купона) --}}
                <div class="pt-2 border-t border-dashed border-gray-100 mt-auto">
                    <div class="flex items-center justify-between text-[9px] sm:text-[10px] font-black uppercase tracking-tight text-gray-400">
                        <div class="flex flex-col items-center text-center gap-0.5 shrink-0">
                            <span class="text-base">✅</span>
                            <span>Купи <br>товар</span>
                        </div>
                        <div class="h-0.5 flex-1 bg-gray-100 mx-1"></div>
                        <div class="flex flex-col items-center text-center gap-0.5 shrink-0 text-orange-600">
                            <span class="text-base">🎉</span>
                            <span>Получи <br> бонус</span>
                        </div>
                        <div class="h-0.5 flex-1 bg-gray-100 mx-1"></div>
                        <div class="flex flex-col items-center text-center gap-0.5 shrink-0 text-pink-600">
                            <span class="text-base">🎁</span>
                            <span>Выиграй <br> приз</span>
                        </div>
                    </div>
                </div>

            </div>
        </article>
    </a>
@endforeach
