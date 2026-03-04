@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="py-6 space-y-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-rose-500 to-orange-500 p-8 rounded-3xl shadow-xl relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/20 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <h1 class="text-3xl md:text-4xl font-black text-white leading-tight tracking-tight">
                    СОЗДАВАЙТЕ <br> <span class="text-rose-100 uppercase">Крутые подарки</span>
                </h1>
            </div>

            <a href="{{ route('partner.gift.create') }}"
               class="relative z-10 inline-flex items-center justify-center px-8 py-4 bg-white text-rose-600 font-bold rounded-2xl shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all active:scale-95">
                <span class="mr-2 text-2xl">+</span> новый подарок
            </a>
        </div>

        <div class="relative group">
            <div class="tabcontent__slider swiper !pb-14">
                <div class="swiper-wrapper">
                    @foreach($partnerGifts as $partnerGift)
                        <div class="swiper-slide h-auto">
                            <div class="h-full bg-white rounded-3xl border border-gray-100 shadow-sm shadow-xl transition-all duration-300 p-6 flex flex-col">

                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="space-y-2">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold">Кол-во</span>
                                            <span class="text-sm font-bold text-gray-700">0</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold">Остаток</span>
                                            <span class="text-sm font-bold text-rose-500">0</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold">Клиентов</span>
                                            <span class="text-sm font-bold text-gray-700">0</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2 border-l border-gray-50 pl-4">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold italic">При заказе от</span>
                                            <span class="text-sm font-bold text-gray-900">{{ number_format($partnerGift->min_order_total, 0, '.', ' ') }} ₸</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold">Шанс</span>
                                            <span class="text-sm font-bold text-blue-600">{{ $partnerGift->chance }}%</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold">Доход</span>
                                            <span class="text-sm font-bold text-green-600">0 ₸</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between bg-gray-50 rounded-2xl p-3 mb-6">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        <span class="text-xs font-bold text-gray-600">4.6</span>
                                    </div>
                                    <button class="text-[10px] uppercase font-black text-indigo-600 hover:text-indigo-800 transition-colors">
                                        отзывы
                                    </button>
                                </div>

                                <div class="mt-auto relative bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-5 overflow-hidden group/card">
                                    <img src="/images/mypromo/slider-card-elem.svg"
                                         alt="element"
                                         class="absolute -right-2 -bottom-2 w-16 opacity-20 transition-transform group-hover/card:scale-125">

                                    <div class="relative z-10">
                                        <h3 class="text-white font-bold text-sm leading-tight mb-1">
                                            {{ \Illuminate\Support\Str::limit($partnerGift->title, 20) }}
                                        </h3>
                                        <p class="text-gray-400 text-[10px]">
                                            условие: от {{ number_format($partnerGift->min_order_total, 0, '.', ' ') }}₸
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="tabcontent__slider-prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 md:-translate-x-6 z-10 w-11 h-11 rounded-full bg-white shadow-lg border border-gray-100 flex items-center justify-center text-rose-500 hover:bg-rose-500 hover:text-white transition-all opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-left text-sm"></i>
                </button>
                <button class="tabcontent__slider-next absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 md:translate-x-6 z-10 w-11 h-11 rounded-full bg-white shadow-lg border border-gray-100 flex items-center justify-center text-rose-500 hover:bg-rose-500 hover:text-white transition-all opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-right text-sm"></i>
                </button>

                <div class="swiper-pagination !bottom-2"></div>
            </div>
        </div>
    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper(".tabcontent__slider", {
                slidesPerView: 1,
                spaceBetween: 24,
                navigation: {
                    nextEl: ".tabcontent__slider-next",
                    prevEl: ".tabcontent__slider-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 }
                }
            });
        });
    </script>
@endpush
