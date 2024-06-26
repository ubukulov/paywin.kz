<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Поздравляем!</title>
    <link rel="stylesheet" href="/b5/css/style.css">
    <script src="/b5/js/script.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/b5/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/b5/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/b5/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="/b5/img/favicons/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body class="container thanks-page">
<h1 class="h1 animate__animated animate__fadeInLeft">Поздравляем!</h1>
<svg class="thanks__check animate__animated animate__fadeIn" width="89" height="89" viewBox="0 0 89 89" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="44.5" cy="44.5" r="41.5" stroke="#17D200" stroke-width="6"></circle>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M64.6301 27.8881C63.2252 26.9616 61.3277 27.3464 60.3931 28.7332L40.3121 58.6576L27.7127 49.2543C26.2882 48.354 24.4002 48.7638 23.4844 50.1606C22.5734 51.5676 22.9831 53.4348 24.4029 54.3357L39.539 65.3546C39.7873 65.5107 40.0517 65.6264 40.3215 65.7052C40.3229 65.7052 40.3242 65.7065 40.3249 65.7065C40.4527 65.7435 40.5826 65.7697 40.7125 65.7906C40.7273 65.792 40.7421 65.7973 40.7569 65.8C40.9009 65.8195 41.0455 65.8303 41.1895 65.8303C41.3147 65.8303 41.4378 65.8222 41.5616 65.8081C41.6599 65.796 41.7581 65.7758 41.855 65.7549C41.8785 65.7496 41.9021 65.7475 41.925 65.7422C42.2829 65.6554 42.6247 65.504 42.9322 65.294C42.9343 65.2927 42.9363 65.2913 42.9376 65.29C43.0258 65.2308 43.1092 65.1635 43.1913 65.0928C43.2061 65.0787 43.2229 65.0693 43.2377 65.0558C43.3023 64.9973 43.3615 64.934 43.4201 64.8708C43.4504 64.8392 43.4826 64.8102 43.5109 64.7773C43.5533 64.7288 43.591 64.675 43.6314 64.6232C43.6684 64.5747 43.7074 64.529 43.7424 64.4778L65.488 32.0733C66.424 30.6812 66.0391 28.8093 64.6301 27.8881Z" fill="#17D200"></path>
</svg>
<p class="thanks__text animate__animated animate__fadeInUp">успешно оплачено</p>
<p class="thanks__number animate__animated animate__fadeInUp" style="margin-bottom: 0px !important;">5000 ₸</p>
<p class="thanks__offer" style="margin-top: 0px;">12.05.2022</p>
<h2 class="banner__title">Вы выиграли приз</h2>
<div class="banner thanks__banner animate__animated animate__fadeIn">
    <p class="banner__text">Донер<br>до 3500₸</p>
</div>
<p class="thanks__offer">Покажите чек партнёру</p>
<div class="thanks__buttons">
    <h2 class="buttons__question">Вы получили приз?</h2>
    <div id="dis_b" class="flex" style="margin-bottom: 20px;">
        <button onclick="clickBtn()" class="button--green animate__animated animate__fadeInLeft">да</button>
        <a href="{{ route('notGivenPrize') }}" style="margin-left: 20px; text-align: center; font-size: 16px;" class="button--grey animate__animated animate__fadeInRight">нет</a>
    </div>

    <div id="suc" style="display: none;">
        <p>Благодарим за покупку, надеемся Вам все понравилось и ждём с радостью Вас снова)</p>
    </div>

    <a href="{{ route('review') }}" class="button--grey button--grey-big animate__animated animate__fadeInUp">оставить оценку и отзыв</a>
</div>
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
<script type="text/javascript">
    function clickBtn(){
        var dis_b = document.getElementById('dis_b');
        var suc = document.getElementById('suc');

        dis_b.style.display = 'none';
        suc.style.display = 'block';
    }
</script>
</body>
</html>
