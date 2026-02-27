@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
    <style>
        /* Глобальный запрет горизонтального скролла для всей страницы */
        html, body {
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
        }

        /* Чтобы тени карточек не обрезались, но и не создавали скролл */
        .swiper {
            overflow: clip !important;
            padding: 20px 0 !important;
            margin: -20px 0 !important;
        }

        /* Фикс для кнопок на средних экранах */
        @media (max-width: 1280px) {
            .active-prev, .old-prev { left: 10px !important; transform: translateY(-50%) !important; }
            .active-next, .old-next { right: 10px !important; transform: translateY(-50%) !important; }
        }
    </style>
@endpush

@section('content')
    {{-- Главная обертка с защитой от вылетающих элементов --}}
    <div class="py-6 space-y-12 max-w-full overflow-x-hidden">

        <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-violet-600 to-fuchsia-600 p-8 rounded-[2.5rem] shadow-xl">
            {{-- Декоративный блюр - обязательно внутри overflow-hidden --}}
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <h1 class="text-3xl md:text-4xl font-black text-white leading-tight tracking-tight uppercase">
                    Создавайте <br> <span class="text-violet-200">Крутые акции</span>
                </h1>
            </div>

            <a href="{{ route('partner.my-shares.create') }}"
               class="relative z-10 inline-flex items-center justify-center px-8 py-4 bg-white text-violet-700 font-bold rounded-2xl shadow-lg hover:shadow-2xl transition-all active:scale-95 whitespace-nowrap">
                <span class="mr-2 text-2xl">+</span> новая акция
            </a>
        </div>

        <div class="relative px-2 md:px-12">
            <div class="tabcontent__slider active-shares-swiper !pb-14">
                <div class="swiper-wrapper">
                    @foreach($shares as $share)
                        @include('partner.partials._share_card', ['share' => $share, 'isActive' => true])
                    @endforeach
                </div>

                <button class="active-prev absolute left-0 xl:-left-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white shadow-xl flex items-center justify-center text-violet-600 hover:bg-violet-600 hover:text-white transition-all border border-gray-100 hidden md:flex">
                    <i class="fas fa-chevron-left text-sm"></i>
                </button>
                <button class="active-next absolute right-0 xl:-right-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white shadow-xl flex items-center justify-center text-violet-600 hover:bg-violet-600 hover:text-white transition-all border border-gray-100 hidden md:flex">
                    <i class="fas fa-chevron-right text-sm"></i>
                </button>

                <div class="swiper-pagination active-pagination"></div>
            </div>
        </div>

        <div class="flex items-center gap-6 py-4 px-4">
            <h2 class="text-xl font-black text-gray-400 uppercase tracking-[0.2em] whitespace-nowrap">Старые акции</h2>
            <div class="h-[2px] bg-gray-100 w-full rounded-full"></div>
        </div>

        <div class="relative px-2 md:px-12 opacity-80 hover:opacity-100 transition-opacity duration-500">
            <div class="tabcontent__slider old-shares-swiper !pb-14">
                <div class="swiper-wrapper">
                    @foreach($shares_old as $share)
                        @include('partner.partials._share_card', ['share' => $share, 'isActive' => false])
                    @endforeach
                </div>

                <button class="old-prev absolute left-0 xl:-left-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white shadow-xl flex items-center justify-center text-gray-400 hover:bg-gray-600 hover:text-white transition-all border border-gray-100 hidden md:flex">
                    <i class="fas fa-chevron-left text-sm"></i>
                </button>
                <button class="old-next absolute right-0 xl:-right-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-white shadow-xl flex items-center justify-center text-gray-400 hover:bg-gray-600 hover:text-white transition-all border border-gray-100 hidden md:flex">
                    <i class="fas fa-chevron-right text-sm"></i>
                </button>

                <div class="swiper-pagination old-pagination"></div>
            </div>
        </div>
    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commonOptions = {
                slidesPerView: 1,
                spaceBetween: 24,
                watchOverflow: true,
                pagination: { clickable: true },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 }
                }
            };

            new Swiper(".active-shares-swiper", {
                ...commonOptions,
                navigation: { nextEl: ".active-next", prevEl: ".active-prev" },
                pagination: { el: ".active-pagination", clickable: true },
            });

            new Swiper(".old-shares-swiper", {
                ...commonOptions,
                navigation: { nextEl: ".old-next", prevEl: ".old-prev" },
                pagination: { el: ".old-pagination", clickable: true },
            });
        });
    </script>
@endpush
