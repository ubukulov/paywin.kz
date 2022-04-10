@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/css/style.min.css">
    <div class="container">
        <div class="partdescr">
            <div class="partdescr__header">
                <div class="partdescr__header-slider swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden">
                    <div class="swiper-wrapper" id="swiper-wrapper-0adf6d1010e59b0c13" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
                        @foreach($partner->images as $image)
                            <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 5" style="width: 480px;">
                                <img src="{{ $image->image }}" alt="photo">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination swiper-pagination-fraction swiper-pagination-horizontal"><span class="swiper-pagination-current">1</span> / <span class="swiper-pagination-total">5</span></div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                <a href="#" class="partdescr__back-btn">
                    <img src="/images/partner-description/left-arrow.svg" alt="back">
                </a>
                <a href="#" class="partdescr__share">
                    <img src="/images/partner-description/share.svg" alt="share">
                </a>
                <a href="#" class="partdescr__camera">
                    <img src="/images/partner-description/camera-icon.svg" alt="camera">
                </a>
                <div class="partdescr__rating-block">
                    <div class="partdescr__rating-num">4.2</div>
                    <div class="partdescr__rating-stars">
                        <i class="fas fa-star full"></i>
                        <i class="fas fa-star full"></i>
                        <i class="fas fa-star full"></i>
                        <i class="fas fa-star full"></i>
                        <i class="fas fa-star empty"></i>
                    </div>
                    <div class="partdescr__rating-score">
                        <span>350</span>
                        оценок
                    </div>
                </div>
            </div>
            <div class="partdescr__main">
                <div class="partdescr__main-fb">
                    <div class="partdescr__profile">
                        <div class="partdescr__profile-logo">
                            <img src="{{ $profile->logo }}" alt="logo">
                        </div>
                        <div class="partdescr__profile-block">
                            <div class="partdescr__profile-name">{{ $profile->company }}</div>
                            <div class="partdescr__profile-descr">{{ $profile->category->title }}</div>
                        </div>
                    </div>
                    <div class="partdescr__gift-slider swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden">
                        <div class="swiper-wrapper" id="swiper-wrapper-2bcb5a4d8cbc10f3b" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
                            <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 4" style="width: 130px;">
                                <div class="partdescr__gift-slider-info">при заказе от 2000 тг</div>
                                <div class="partdescr__gift-slider-card">
                                    <img src="/images/partner-description/slider-elem.svg" alt="">
                                    <p>Пицца <br> до 50000₸</p>
                                </div>
                            </div>
                            <div class="swiper-slide swiper-slide-next" role="group" aria-label="2 / 4" style="width: 130px;">
                                <div class="partdescr__gift-slider-info">при заказе от 2000 тг</div>
                                <div class="partdescr__gift-slider-card">
                                    <img src="/images/partner-description/slider-elem.svg" alt="">
                                    <p>Пицца <br> до 50000₸</p>
                                </div>
                            </div>
                            <div class="swiper-slide" role="group" aria-label="3 / 4" style="width: 130px;">
                                <div class="partdescr__gift-slider-info">при заказе от 2000 тг</div>
                                <div class="partdescr__gift-slider-card">
                                    <img src="/images/partner-description/slider-elem.svg" alt="">
                                    <p>Пицца <br> до 50000₸</p>
                                </div>
                            </div>
                            <div class="swiper-slide" role="group" aria-label="4 / 4" style="width: 130px;">
                                <div class="partdescr__gift-slider-info">при заказе от 2000 тг</div>
                                <div class="partdescr__gift-slider-card">
                                    <img src="/images/partner-description/slider-elem.svg" alt="">
                                    <p>Пицца <br> до 50000₸</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination swiper-pagination-bullets swiper-pagination-horizontal"><span class="swiper-pagination-bullet swiper-pagination-bullet-active" aria-current="true"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span></div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                </div>

                @include('_partials._address')

                <div class="partdescr__description">
                    <div class="partdescr__description-title">Описание</div>
                    <p>
                        {{ $profile->description }}
                    </p>
                </div>
                <div class="partdescr__time">
                    <div class="partdescr__time-title">Время работы</div>
                    <p>
                        {!! $profile->work_time !!}
                    </p>
                </div>
                <div class="partdescr__social">
                    <div class="partdescr__social-title">Социальные сети</div>
                    <div class="partdescr__social-block">
                        @if(!empty($profile->vk))
                        <a href="{{ $profile->vk }}" target="_blank" class="partdescr__social-link">
                            <img src="/images/partner-description/vk-icon.svg" alt="vk">
                        </a>
                        @endif

                        @if(!empty($profile->telegram))
                        <a href="{{ $profile->telegram }}" target="_blank" class="partdescr__social-link">
                            <img src="/images/partner-description/telegram-icon.svg" alt="telegram">
                        </a>
                        @endif

                        @if(!empty($profile->instagram))
                        <a href="{{ $profile->instagram }}" target="_blank" class="partdescr__social-link">
                            <img src="/images/partner-description/insta-icon.svg" alt="instagram">
                        </a>
                        @endif
                    </div>
                </div>
                <div class="partdescr__pay">
                    <a href="{{ route('paymentPage', ['slug' => $slug, 'id' => $id]) }}" class="partdescr__pay-block">
                        <img src="/images/partner-description/pay-icon.svg" alt="icon">
                        оплатить
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".partdescr__header-slider", {
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: ".swiper-pagination",
                type: "fraction",
            },
        });
        var swiper2 = new Swiper(".partdescr__gift-slider", {
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: ".swiper-pagination",
            },
        });
        var swiper3 = new Swiper(".partdescr__address-slider", {
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: ".swiper-pagination",
            },
        });
    </script>
    <script src="/js/main.min.js"></script>
@stop
