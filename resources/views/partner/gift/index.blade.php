@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="mypromo">
        <div class="mypromo__header">
            <div class="mypromo__header-title">
                СОЗДАВАЙТЕ <br> КРУТЫЕ ПОДАРКИ
            </div>
            <a href="{{ route('partner.gift.create') }}" class="mypromo__header-btn">+ новый подарок</a>
        </div>

        <div class="mypromo__tabs">
            <div class="tabcontent">
                <div class="tabcontent__wrapper">
                    <div class="tabcontent__slider">
                        <div class="swiper-wrapper">
                            @foreach($partnerGifts as $partnerGift)
                                <div class="tabcontent__slider-item swiper-slide">
                                    <div class="tabcontent__slider-top">
                                        <div class="tabcontent__slider-left">
                                            <p>Кол-во: <span>0</span></p>
                                            <p>Остаток: <span>0</span></p>
                                            <p>Клиентов: <span>0</span></p>
                                        </div>
                                        <div class="tabcontent__slider-right">
                                            <p>При заказе от: <span>{{ $partnerGift->min_order_total }}</span></p>
                                            <p>Коэф выигр: <span>{{ $partnerGift->chance }}%</span></p>
                                            <p>Доход: <span>0 ₸</span></p>
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
                                            <p>{{ \Illuminate\Support\Str::limit($partnerGift->title, 14) }} <br>
                                                при заказе от {{ $partnerGift->min_order_total }}₸</p>
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
        </div>

    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="/js/my-promo-promo.js"></script>
    <script src="/js/about-partner.js"></script>
@endpush
