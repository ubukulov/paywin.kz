<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Призы</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/script.js"></script>
    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="/js/slick.min.js"></script>
    <script src="/js/payment.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="/img/favicons/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/swiper-boundle.min.css">
    <link rel="stylesheet" href="/css/style.min.css">
    <style>
        .review__form-btn {
            display: block;
            margin: 30px auto 0;
            background: #FD9B11;
            border: none;
            border-radius: 28px;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            line-height: 20px;
            -webkit-box-shadow: #FD9B11 28px;
            box-shadow: #FD9B11 28px;
            padding: 6px 55px 8px 42px;
            cursor: pointer;
            -webkit-box-shadow: 0px 0px 23px #fd9b11;
            box-shadow: 0px 0px 23px #fd9b11;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-column-gap: 7.5px;
            -moz-column-gap: 7.5px;
            column-gap: 7.5px;
        }
        .myprize {
            height: auto !important;
            padding-bottom: 40px;
        }
        .myprize__card-text {
            font-size: 12px !important;
        }
        .winners__top-card {
            line-height: 11px;
            align-content: center;
        }
    </style>
</head>
<body class="container prizes-page">
    {{--<header class="header prizes-page__header">
        <a href="#" class="header__categories">
            <img src="/img/icons/header-categories.svg" alt="Открыть меню">
        </a>
        <button class="switch-btn switch-on"></button>
        <img src="/img/icons/header-location.svg" alt="Местоположение" class="header__geo">
        <nav class="nav header__nav">
            <a href="#" class="nav__link nav__link--active">Призы</a>
            <a href="#" class="nav__link">Победители</a>
            <a href="#" class="nav__link">Мои призы</a>
        </nav>
    </header>--}}
    <main>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            {{--<li class="nav-item" role="presentation">
                <a class="nav-link active promo__nav-link" id="prize-tab" data-toggle="tab" href="#prize" role="tab" aria-controls="prize" aria-selected="true">Призы</a>
            </li>--}}

            <li class="nav-item" role="presentation">
                <a class="nav-link active promo__nav-link" id="discount-tab" data-toggle="tab" href="#discount" role="tab" aria-controls="discount" aria-selected="false">Победители</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link promo__nav-link" id="cashback-tab" data-toggle="tab" href="#cashback" role="tab" aria-controls="cashback" aria-selected="false">Мои призы</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            {{--<div class="tab-pane fade show active" id="prize" role="tabpanel" aria-labelledby="prize-tab">
                @each('_partials._prizes', $shares, 'share')
            </div>--}}

            <div class="tab-pane fade show active" id="discount" role="tabpanel" aria-labelledby="discount-tab">
{{--                @include('_partials._winners', $winners, 'winner')--}}
                <div class="winners" style="margin-top: 0px; padding-top: 0px; height: auto;">
                    <div class="winners__wrapper" style="padding-top: 20px; margin-top: 0px;">
                        <div class="winners__top-slider">
                            <div class="winners__top-slider-title">
                                Выиграли призов: <p><span>{{ count($winners) }}</span> за месяц</p>
                            </div>
                            <div class="winners__top-slider-wrapper">
                                <div class="swiper-wrapper">
                                    @foreach($winners as $winner)
                                    <div class="winners__top-item swiper-slide">
                                        <div class="winners__top-avatar">
                                            <img src="/images/winners/avatar.png" alt="">
                                        </div>
                                        <div class="winners__top-info">
                                            {{ $winner->full_name }} выиграл приз
                                        </div>

                                        <div class="winners__top-card @if($winner->share_type == 'share') purple @elseif($winner->share_type == 'cashback') red @else blue @endif">
                                            {{ $winner->share_title }}
                                        </div>

                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="winners__top-slider-next"><img src="/images/winners/next-arrow.svg" alt=""></div>
                            <div class="winners__top-slider-prev"><img src="/images/winners/prev-arrow.svg" alt=""></div>
                        </div>
                        <div class="winners__bottom-slider">
                            <div class="winners__bottom-slider-title">TOP партнеров</div>
                            <div class="winners__bottom-slider-wrapper">
                                <div class="swiper-wrapper">

                                    @foreach($top_partners as $top)
                                    <div class="winners__bottom-item swiper-slide">
                                        <div class="winners__bottom-logo">
                                            <img @if(empty($top->profile->logo)) src="/images/winners/kfc-logo.png" @else src="{{ asset($top->profile->logo) }}" @endif alt="">
                                        </div>
                                        <div class="winners__bottom-name">{{ $top->profile->company }}</div>
                                        <div class="winners__bottom-info">
                                            вручили призов: <p><span>{{ $top->getCountOfAwardedPrizes() }}</span> за месяц</p>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                            <div class="winners__bottom-slider-next"><img src="/images/winners/next-arrow.svg" alt=""></div>
                            <div class="winners__bottom-slider-prev"><img src="/images/winners/prev-arrow.svg" alt=""></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="cashback" role="tabpanel" aria-labelledby="cashback-tab">
                @each('_partials._myprizes', $prizes, 'prize')
            </div>
        </div>
    </main>

{{--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>--}}
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
<script src="/js/swiper-boundle.min.js"></script>
<script src="/js/winners.js"></script>
<footer class="footer container animate__animated animate__fadeInUp">
    <a href="{{ route('prizes') }}" class="footer__link">
        <img src="/img/icons/footer-gift-active.svg" alt="Подарок" class="footer__icon">
    </a>
    <a href="{{ route('home') }}" class="footer__link">
        <img src="/img/icons/footer-qr-grey.svg" alt="QR код" class="footer__icon">
    </a>
    <a @if(Auth::user()->user_type == 'partner') href="{{ route('partner.cabinet') }}" @else href="{{ route('user.cabinet') }}" @endif class="footer__link">
        <img src="/img/icons/footer-user.svg" alt="Профиль" class="footer__icon">
    </a>
</footer>
</body>
</html>
