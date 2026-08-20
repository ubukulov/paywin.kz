@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
    <style>
        /* Стили для того, чтобы кнопки Swiper были видны поверх карточек */
        .swiper-button-next, .swiper-button-prev {
            @apply bg-white w-10 h-10 rounded-full shadow-lg text-indigo-600 after:text-sm font-bold border border-gray-100 transition-all hover:bg-indigo-50;
        }
    </style>
@endpush

@section('content')
    <div class="py-6 space-y-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-indigo-700 to-purple-800 p-8 rounded-3xl shadow-xl overflow-hidden relative">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <h1 class="text-3xl md:text-4xl font-black text-white leading-tight tracking-tight uppercase">
                    Создавайте <br> <span class="text-indigo-200">крутые товары</span>
                </h1>
            </div>

            <a href="{{ route('partner.product.create') }}"
               class="relative z-10 inline-flex items-center justify-center px-6 py-3 bg-white text-indigo-700 font-bold rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all active:scale-95 whitespace-nowrap">
                <span class="mr-2 text-xl">+</span> новый товар
            </a>
        </div>

        <div class="relative px-4 md:px-0">
            <div class="tabcontent">
                <div class="tabcontent__slider swiper !pb-12"> <div class="swiper-wrapper">
                        @foreach($products as $product)
                            <div class="swiper-slide h-auto"> <article class="group h-full bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl border-indigo-100 transition-all duration-300 flex flex-col">

                                    <div class="relative overflow-hidden aspect-square">
                                        <img src="{{ $product->mainImage->url }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                             loading="lazy" />
                                    </div>

                                    <div class="p-5 flex flex-col flex-1">
                                        <h3 class="text-sm font-bold text-gray-800 line-clamp-2 min-h-[40px] mb-2 leading-snug">
                                            {{ $product->name }}
                                        </h3>

                                        <div class="mt-auto">
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex flex-col">
                                                    <span class="text-xs text-gray-400 font-medium uppercase tracking-tighter italic">Цена:</span>
                                                    <div class="flex items-baseline gap-1">
                                                        <span class="text-xl font-black text-gray-900">{{ number_format($product->price, 0, '.', ' ') }}</span>
                                                        <span class="text-sm font-bold text-gray-500 text-xs">₸</span>
                                                    </div>
                                                </div>

                                                <a href="{{ route('partner.product.edit', $product->id) }}"
                                                   class="flex items-center justify-center w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            </div>

                                            <div class="flex items-center justify-between pt-3 border-t border-gray-50 text-[11px]">
                                                <div class="text-gray-500 italic">На складе: <span class="font-bold text-gray-700 not-italic">{{ $product->quantity }} шт</span></div>
                                                <div class="text-gray-300 font-mono">#{{ $product->sku }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>

                    <div class="hidden md:block">
                        <button class="tabcontent__slider-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-6 z-10 bg-white w-12 h-12 rounded-full shadow-xl flex items-center justify-center text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all border border-gray-100">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="tabcontent__slider-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-6 z-10 bg-white w-12 h-12 rounded-full shadow-xl flex items-center justify-center text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all border border-gray-100">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="swiper-pagination !-bottom-2"></div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Инициализация Swiper прямо здесь для наглядности (можно оставить в ваших файлах)
        new Swiper(".tabcontent__slider", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".tabcontent__slider-next",
                prevEl: ".tabcontent__slider-prev",
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
                1280: { slidesPerView: 4 }
            }
        });
    </script>
@endpush
