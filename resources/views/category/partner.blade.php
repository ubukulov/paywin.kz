@extends('layouts.app')

@section('content')
    <div class="partdescr max-w-2xl mx-auto bg-white min-h-screen pb-24 relative">

        {{-- Header с основным слайдером --}}
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-50">
            @include('partner.partials._images')
        </div>

        <div class="partdescr__main px-4 pt-6">
            {{-- Профиль и Слайдер подарков --}}
            <div class="flex flex-col gap-6 mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex-shrink-0">
                        <img src="{{ empty($profile->logo) ? '/images/partner-description/partner-logo.png' : $profile->logo }}"
                             alt="logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 leading-tight">{{ $profile->company }}</h1>
                        <p class="text-sm text-gray-500">{{ $profile->category->title }}</p>
                    </div>
                </div>

                {{-- Слайдер акций/подарков --}}
                <div class="partdescr__gift-slider overflow-hidden">
                    <div class="swiper-wrapper py-2">
                        @foreach($partner->shares as $share)
                            <div class="swiper-slide !w-auto">
                                <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4 min-w-[200px] h-full flex flex-col justify-between">
                                    <div class="text-[10px] font-bold text-orange-400 uppercase mb-2">при заказе от {{ number_format($share->from_order, 0, '.', ' ') }} ₸</div>
                                    <div class="flex items-start gap-3">
                                        <img src="/images/partner-description/slider-elem.svg" alt="" class="">
                                        <p class="text-sm font-semibold text-gray-800 leading-snug">
                                            {{ \Illuminate\Support\Str::limit($share->title, 14) }} <br>
                                            {{--<span class="text-xs font-normal text-gray-500">до {{ number_format($share->to_order, 0, '.', ' ') }}₸</span>--}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Карты и Адреса --}}
            <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-50">
                @include('partner.partials._address')
            </div>

            {{-- Описание --}}
            <div class="mb-8 border-t border-gray-100 pt-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2 font-['Inter']">Описание</h3>
                <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                    {{ $profile->description }}
                </p>
            </div>

            {{-- Время работы --}}
            <div class="mb-8 p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                <h3 class="text-sm font-bold text-blue-900 uppercase mb-2">Время работы</h3>
                <div class="text-blue-800 text-sm">
                    {!! $profile->work_time !!}
                </div>
            </div>

            {{-- Соцсети --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Социальные сети</h3>
                <div class="flex gap-3">
                    @if(!empty($profile->vk))
                        <a href="{{ $profile->vk }}" target="_blank" class="w-12 h-12 bg-gray-50 flex items-center justify-center rounded-xl hover:bg-blue-50 transition-colors shadow-sm">
                            <img src="/images/partner-description/vk-icon.svg" alt="vk" class="w-6 h-6">
                        </a>
                    @endif
                    @if(!empty($profile->telegram))
                        <a href="{{ $profile->telegram }}" target="_blank" class="w-12 h-12 bg-gray-50 flex items-center justify-center rounded-xl hover:bg-blue-50 transition-colors shadow-sm">
                            <img src="/images/partner-description/telegram-icon.svg" alt="telegram" class="w-6 h-6">
                        </a>
                    @endif
                    @if(!empty($profile->instagram))
                        <a href="{{ $profile->instagram }}" target="_blank" class="w-12 h-12 bg-gray-50 flex items-center justify-center rounded-xl hover:bg-pink-50 transition-colors shadow-sm">
                            <img src="/images/partner-description/insta-icon.svg" alt="instagram" class="w-6 h-6">
                        </a>
                    @endif
                </div>
            </div>

            {{-- Кнопка Оплаты (липкая снизу) --}}
            <div class="fixed bottom-6 left-0 right-0 px-4 z-40 max-w-2xl mx-auto">
                <a href="{{ route('paymentPage', ['slug' => $slug, 'id' => $id]) }}"
                   class="flex items-center justify-center gap-3 w-full bg-[#FD9B11] text-white font-bold text-lg py-4 rounded-3xl shadow-[0_10px_30px_rgba(253,155,17,0.4)] hover:scale-[1.02] transition-transform active:scale-95 uppercase tracking-wide">
                    <img src="/images/partner-description/pay-icon.svg" alt="icon" class="w-6 h-6 brightness-0 invert">
                    оплатить
                </a>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="/css/swiper-boundle.min.css">
    <script src="/js/swiper-boundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Главный слайдер
            new Swiper('.partdescr__header-slider', {
                pagination: { el: '.swiper-pagination', clickable: true },
                autoplay: { delay: 3000 },
            });

            // Слайдер подарков
            new Swiper('.partdescr__gift-slider', {
                slidesPerView: 'auto',
                spaceBetween: 12,
                freeMode: true,
            });
        });
    </script>
@stop
