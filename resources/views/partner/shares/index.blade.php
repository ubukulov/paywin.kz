@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="mypromo">
        <div class="mypromo__header">
            <div class="mypromo__header-title">
                СОЗДАВАЙТЕ <br> КРУТЫЕ АКЦИИ
            </div>
            <a href="{{ route('partner.my-shares.create') }}" class="mypromo__header-btn">+ новая акция</a>
        </div>
        {{--<div class="mypromo__nav">
            <ul>
                <li><a href="#" class="mypromo__nav-link mypromo__nav-link-active">акции</a></li>
                <li><a href="#" class="mypromo__nav-link">промокоды</a></li>
            </ul>
        </div>--}}
        <div class="mypromo__tabs">
            {{--<div class="tabheader">
                <div class="tabheader__items">
                    <div class="tabheader__item tabheader__item_active">активные</div>
                    <div class="tabheader__item">прошедшие</div>
                </div>
            </div>--}}
            <div class="tabcontent">
                <div class="tabcontent__wrapper">
                    <div class="tabcontent__slider">
                        <div class="swiper-wrapper">
                            @foreach($shares as $share)
                            <div class="tabcontent__slider-item swiper-slide">
                                <button class="tabcontent__slider-btn">изменить акцию</button>
                                <div class="tabcontent__slider-top">
                                    <div class="tabcontent__slider-left">
                                        <p>Кол-во: <span>{{ $share->cnt }}</span></p>
                                        <p>Остаток: <span>2</span></p>
                                        <p>Клиентов: <span>6</span></p>
                                    </div>
                                    <div class="tabcontent__slider-right">
                                        <p>При заказе от: <span>{{ $share->from_order }}</span></p>
                                        <p>Коэф выигр: <span>{{ $share->c_winning }}%</span></p>
                                        <p>Доход: <span>250000₸</span></p>
                                    </div>
                                </div>
                                <div class="tabcontent__slider-center">
                                    <i class="fas fa-star"></i>
                                    <div class="tabcontent__slider-center-text">Оценка акции: <span>4.6</span></div>
                                    <button class="tabcontent__slider-center-btn">отзывы</button>
                                </div>
                                <div class="tabcontent__slider-bottom">
                                    <div class="tabcontent__slider-card">
                                        <img src="/images/mypromo/slider-card-elem.svg" alt="element">
                                        <p>Баскет {{ $share->cnt }} крыльев <br>
                                            при заказе от {{ $share->from_order }}₸</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="tabcontent__slider-next"><img src="/images/mypromo/next-arrow.svg" alt="btn"></div>
                        <div class="tabcontent__slider-prev"><img src="/images/mypromo/prev-arrow.svg" alt="btn"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
            <div class="tabcontent">
                <div class="tabcontent__wrapper">
                    <div class="tabcontent__slider">
                        <div class="swiper-wrapper">
                            <div class="tabcontent__slider-item swiper-slide">
                                <button class="tabcontent__slider-btn">изменить акцию</button>
                                <div class="tabcontent__slider-top">
                                    <div class="tabcontent__slider-left">
                                        <p>Кол-во: <span>10</span></p>
                                        <p>Остаток: <span>2</span></p>
                                        <p>Клиентов: <span>6</span></p>
                                    </div>
                                    <div class="tabcontent__slider-right">
                                        <p>При заказе от: <span>5000</span></p>
                                        <p>Коэф выигр: <span>80%</span></p>
                                        <p>Доход: <span>250000₸</span></p>
                                    </div>
                                </div>
                                <div class="tabcontent__slider-center">
                                    <i class="fas fa-star"></i>
                                    <div class="tabcontent__slider-center-text">Оценка акции: <span>4.6</span></div>
                                    <button class="tabcontent__slider-center-btn">отзывы</button>
                                </div>
                                <div class="tabcontent__slider-bottom">
                                    <div class="tabcontent__slider-card">
                                        <img src="/images/mypromo/slider-card-elem.svg" alt="element">
                                        <p>Баскет 25 крыльев <br>
                                            при заказе от 5000₸</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tabcontent__slider-item swiper-slide">
                                <button class="tabcontent__slider-btn">изменить акцию</button>
                                <div class="tabcontent__slider-top">
                                    <div class="tabcontent__slider-left">
                                        <p>Кол-во: <span>10</span></p>
                                        <p>Остаток: <span>2</span></p>
                                        <p>Клиентов: <span>6</span></p>
                                    </div>
                                    <div class="tabcontent__slider-right">
                                        <p>При заказе от: <span>5000</span></p>
                                        <p>Коэф выигр: <span>80%</span></p>
                                        <p>Доход: <span>250000₸</span></p>
                                    </div>
                                </div>
                                <div class="tabcontent__slider-center">
                                    <i class="fas fa-star"></i>
                                    <div class="tabcontent__slider-center-text">Оценка акции: <span>4.6</span></div>
                                    <button class="tabcontent__slider-center-btn">отзывы</button>
                                </div>
                                <div class="tabcontent__slider-bottom">
                                    <div class="tabcontent__slider-card">
                                        <img src="/images/mypromo/slider-card-elem.svg" alt="element">
                                        <p>Баскет 25 крыльев <br>
                                            при заказе от 5000₸</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tabcontent__slider-next"><img src="/images/mypromo/next-arrow.svg" alt="btn"></div>
                        <div class="tabcontent__slider-prev"><img src="/images/mypromo/prev-arrow.svg" alt="btn"></div>
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
