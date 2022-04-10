@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ asset('css/style2.min.css') }}">
    <div class="container">
        <div class="partdescr">
            <div class="partdescr__header">
                <div class="partdescr__header-slider">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img src="/images/partner-description/restaraunt-photo.png" alt="photo">
                        </div>
                        <div class="swiper-slide">
                            <img src="/images/partner-description/restaraunt-photo.png" alt="photo">
                        </div>
                        <div class="swiper-slide">
                            <img src="/images/partner-description/restaraunt-photo.png" alt="photo">
                        </div>
                        <div class="swiper-slide">
                            <img src="/images/partner-description/restaraunt-photo.png" alt="photo">
                        </div>
                        <div class="swiper-slide">
                            <img src="/images/partner-description/restaraunt-photo.png" alt="photo">
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
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
                            <img src="/images/partner-description/partner-logo.png" alt="logo">
                        </div>
                        <div class="partdescr__profile-block">
                            <div class="partdescr__profile-name">Papa John’s</div>
                            <div class="partdescr__profile-descr">Кофейни и десерты</div>
                        </div>
                    </div>
                    <div class="partdescr__gift-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="partdescr__gift-slider-info">при заказе от 2000 тг</div>
                                <div class="partdescr__gift-slider-card">
                                    <img src="/images/partner-description/slider-elem.svg" alt="">
                                    <p>Пицца <br> до 50000₸</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="partdescr__gift-slider-info">при заказе от 2000 тг</div>
                                <div class="partdescr__gift-slider-card">
                                    <img src="/images/partner-description/slider-elem.svg" alt="">
                                    <p>Пицца <br> до 50000₸</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="partdescr__gift-slider-info">при заказе от 2000 тг</div>
                                <div class="partdescr__gift-slider-card">
                                    <img src="/images/partner-description/slider-elem.svg" alt="">
                                    <p>Пицца <br> до 50000₸</p>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="partdescr__gift-slider-info">при заказе от 2000 тг</div>
                                <div class="partdescr__gift-slider-card">
                                    <img src="/images/partner-description/slider-elem.svg" alt="">
                                    <p>Пицца <br> до 50000₸</p>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
                <div class="partdescr__address">
                    <div class="partdescr__address-title">Адреса (3)</div>
                    <div class="partdescr__address-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="partdescr__address-info">
                                    <div class="partdescr__address-info-block">
                                        <img src="/images/partner-description/location.svg" alt="icon">
                                        <div class="partdescr__address-text">
                                            г. Алматы <br>
                                            Улица уличная <br>
                                            Дом 28
                                        </div>
                                    </div>
                                </div>
                                <div class="partdescr__address-map">
                                    <img src="/images/partner-description/partner-map.png" alt="map">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="partdescr__address-info">
                                    <div class="partdescr__address-info-block">
                                        <img src="/images/partner-description/location.svg" alt="icon">
                                        <div class="partdescr__address-text">
                                            г. Алматы <br>
                                            Улица уличная <br>
                                            Дом 28
                                        </div>
                                    </div>
                                </div>
                                <div class="partdescr__address-map">
                                    <img src="/images/partner-description/partner-map.png" alt="map">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="partdescr__address-info">
                                    <div class="partdescr__address-info-block">
                                        <img src="/images/partner-description/location.svg" alt="icon">
                                        <div class="partdescr__address-text">
                                            г. Алматы <br>
                                            Улица уличная <br>
                                            Дом 28
                                        </div>
                                    </div>
                                </div>
                                <div class="partdescr__address-map">
                                    <img src="/images/partner-description/partner-map.png" alt="map">
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
                <div class="partdescr__description">
                    <div class="partdescr__description-title">Описание</div>
                    <p>
                        Здесь должно быть описание заведения, но его нет <br>
                        Здесь должно быть описание заведения, но его нет <br>
                        Здесь должно быть описание заведения, но его нет <br>
                        Здесь должно быть описание заведения, но его нет <br>
                    </p>
                </div>
                <div class="partdescr__time">
                    <div class="partdescr__time-title">Время работы</div>
                    <p>Понедельник <span>с 10:00 до 20:00</span></p>
                    <p>Вторник <span>с 10:00 до 20:00</span></p>
                    <p>Среда <span>с 10:00 до 20:00</span></p>
                    <p>Четверг <span>с 10:00 до 20:00</span></p>
                    <p>Пятница <span>с 10:00 до 20:00</span></p>
                    <p>Суббота <span>выходной</span></p>
                    <p>Воскресенье <span>выходной</span></p>
                </div>
                <div class="partdescr__social">
                    <div class="partdescr__social-title">Социальные сети</div>
                    <div class="partdescr__social-block">
                        <a href="#" class="partdescr__social-link">
                            <img src="/images/partner-description/vk-icon.svg" alt="vk">
                        </a>
                        <a href="#" class="partdescr__social-link">
                            <img src="/images/partner-description/telegram-icon.svg" alt="telegram">
                        </a>
                        <a href="#" class="partdescr__social-link">
                            <img src="/images/partner-description/insta-icon.svg" alt="instagram">
                        </a>
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
