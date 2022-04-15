<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Оплата</title>
    <link rel="stylesheet" href="/b5/css/style.css">
    <script src="/b5/js/script.js"></script>
    <script src="/b5/js/jquery-3.6.0.min.js"></script>
    <script src="/b5/js/slick.min.js"></script>
    <script src="/b5/js/payment.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/b5/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/b5/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/b5/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="/b5/img/favicons/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body class="container payment-page">
<h1 class="h1 animate__animated animate__fadeInLeft">Оплата</h1>
<form class="payment-page__form" method="post" action="{{ route('payment') }}">
    @csrf
    <input type="hidden" name="partner_id" value="{{ $id }}">
    <div class="actions">
        <h2 style="margin-top: 0px;" class="offer animate__animated animate__fadeIn">Сумма оплаты</h2>
        <p class="payment-page__number">
            <input style="width: 200px;
            max-width: 100%;
            height: 35px;
            border-radius: 5px;
            border: 1px solid #ccc; text-align: center;" required type="text" name="sum">
        </p>
        <div class="action action--card actions__action">
            <h3 class="action__subtitle">Метод оплаты</h3>
            <div class="action__flex">
                <img src="/b5/img/logotypes/mastercard.svg" alt="mastercard" class="action__icon action__icon--card">
                <p class="action__number action__number--dots">....</p>
                <p class="action__number">2458</p>
                <p class="action__warning">-12250</p>
                <button class="action__button">
                    <img src="/b5/img/icons/arrow-down.svg" alt="Сменить способ оплаты"></button>
            </div>
        </div>
        <div class="action action--wallet actions__action">
            <h3 class="action__subtitle">Потратить баланс</h3>
            <div class="action__flex">
                <img src="/b5/img/icons/wallet.svg" alt="кошёлек" class="action__icon action__icon--wallet">
                <p class="action__number">250₸</p>
                <p class="action__warning">-250</p>
                <button class="switch-btn switch-on action__button action__button--checkbox"></button>
            </div>
        </div>
        <div class="action action--precent actions__action">
            <h3 class="action__subtitle">Применить скидку</h3>
            <div class="action__flex">
                <img src="/b5/img/icons/precent.svg" alt="проценты" class="action__icon action__icon--precent">
                <p class="action__number">-50%</p><p class="action__warning">-6125</p>
                <button class="switch-btn switch-on action__button action__button--checkbox"></button>
            </div>
        </div>
    </div>
    {{--<div class="keyboard keyboard__wrapper animate__animated animate__fadeIn">
        <button class="keyboard__item">1</button> <button class="keyboard__item">2</button>
        <button class="keyboard__item">3</button>
        <button class="keyboard__item">4</button>
        <button class="keyboard__item">5</button>
        <button class="keyboard__item">6</button>
        <button class="keyboard__item">7</button>
        <button class="keyboard__item">8</button>
        <button class="keyboard__item">9</button>
        <div class="keyboard__item keyboard__item--none"></div>
        <button class="keyboard__item">0</button>
        <button class="keyboard__item keyboard__item--delete">
            <img src="/b5/img/icons/delete.svg" alt="Удалить">
        </button>
    </div>--}}
    <div class="payment-page__slider-buttons animate__animated animate__fadeIn">
        <div>
            <h2 class="slider__title">Выиграйте один из призов</h2>
            <div class="slider">

                @foreach($partner->shares as $share)
                <div class="slide slider__item">
                    <p class="slide__text">{{ \Illuminate\Support\Str::limit($share->title, 14) }}<br>до {{ $share->to_order }}₸</p>
                </div>
                @endforeach

            </div>
        </div>
        <div class="payment-page__buttons">
            <button type="submit" class="button button--green">оплатить</button>
            <a href="{{ route('showPartner', ['slug' => $slug, 'id' => $id]) }}" class="button button--back">вернуться</a>
        </div>
    </div>
</form>
<footer class="footer container animate__animated animate__fadeInUp">
    <a href="{{ route('prizes') }}" class="footer__link">
        <img src="/b5/img/icons/footer-gift.svg" alt="Подарок" class="footer__icon">
    </a>
    <a href="{{ route('home') }}" class="footer__link">
        <img src="/b5/img/icons/footer-qr.svg" alt="QR код" class="footer__icon">
    </a>
    <a @if(Auth::user()->user_type == 'partner') href="{{ route('partner.cabinet') }}" @else href="{{ route('user.cabinet') }}" @endif class="footer__link">
        <img src="/b5/img/icons/footer-user.svg" alt="Профиль" class="footer__icon">
    </a>
</footer>
</body>
</html>
