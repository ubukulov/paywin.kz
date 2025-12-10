@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="mypromo">
        <div class="mypromo__header">
            <div class="mypromo__header-title">
                СОЗДАВАЙТЕ <br> КРУТЫЕ ТОВАРЫ
            </div>
            <a href="{{ route('partner.product.create') }}" class="mypromo__header-btn">+ новый товар</a>
        </div>

        <div class="mypromo__tabs">
            <div class="tabcontent">
                <div class="tabcontent__wrapper">
                    <div class="tabcontent__slider swiper">

                        <!-- Swiper wrapper -->
                        <div class="swiper-wrapper">
                            @foreach($products as $product)
                                <!-- Один слайд -->
                                <div class="swiper-slide">
                                    <article
                                        class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">

                                        <div class="relative">
                                            <img src="{{ $product->mainImage->url }}"
                                                 alt="Фото товара"
                                                 class="w-full h-48 object-cover"
                                                 loading="lazy" />

                                            <span class="absolute top-3 left-3 bg-indigo-600 text-white text-xs font-semibold px-2 py-1 rounded">
                                        Новинка
                                    </span>

                                            <button aria-label="Добавить в избранное"
                                                    class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm p-2 rounded-full shadow hover:bg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-5 w-5 text-red-500"
                                                     viewBox="0 0 20 20"
                                                     fill="currentColor">
                                                    <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.343 3.172 10.83a4 4 0 010-5.657z" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="p-4 flex flex-col gap-3">

                                            <h3 class="text-sm font-semibold leading-tight">
                                                {{ $product->name }}
                                            </h3>

                                            <div class="flex items-center justify-between mt-2">
                                                <div class="flex items-baseline gap-2">
                                                    <div class="text-lg font-bold">{{ $product->price }}</div>
                                                    <div class="text-sm text-gray-600">₸</div>
                                                </div>

                                                <button class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700 transition">
                                                    В корзину
                                                </button>
                                            </div>

                                            <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                                                <div>В наличии: <span class="font-medium text-gray-700">{{ $product->quantity }}</span></div>
                                                <div>Артикул: <span class="font-mono">{{ $product->sku }}</span></div>
                                            </div>

                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        <!-- Навигация -->
                        <div class="tabcontent__slider-next">
                            <img src="/images/mypromo/next-arrow.svg" alt="Next">
                        </div>

                        <div class="tabcontent__slider-prev">
                            <img src="/images/mypromo/prev-arrow.svg" alt="Prev">
                        </div>

                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="/js/my-promo-promo.js"></script>
    <script src="/js/about-partner.js"></script>
@endpush
