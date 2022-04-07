<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Главная</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicons/site.webmanifest') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>
<body class="home-page">
<section class="camera">
    <div class="container">
        <h1 class="home-page__title animate__animated animate__fadeInDown">Наведите камеру на QR код</h1>
        <button class="button camera__button">Сканировать</button>
        <div class="camera--hidden animate__animated animate__fadeInDown" style="text-align: center;">
            {{--<img src="/img/icons/qr.svg" alt="Наведите камеру на QR код" class="camera__img">--}}
            <video style="max-width: 100%; height: 200px; transform: scaleX(1) !important;" id="preview"></video>
            <a href="#" class="how-it-works animate__animated animate__fadeIn">
                <img src="/img/icons/how-it-works.svg" alt="info" class="how-it-works__img">
                <p class="how-it-works__text">Как это работает?</p>
            </a>
        </div>
        <form action="" class="home-page__form animate__animated animate__fadeIn">
            <input type="text" name="location" placeholder="Алматы" class="home-page__select">
            <input type="text" placeholder="Искать акцию или компанию.." class="home-page__input">
            <button type="submit" class="home-page__search"><img src="/img/icons/search.svg" alt="Найти"></button>
        </form>

        <script type="text/javascript">
            let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            scanner.addListener('scan', function (content) {
                console.log(content);
                alert(content);
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[1]);
                } else {
                    console.error('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        </script>
    </div>
</section>

@yield('content')

<footer class="footer container animate__animated animate__fadeInUp">
    <a href="{{ route('prizes') }}" class="footer__link">
        <img src="/img/icons/footer-gift.svg" alt="Подарок" class="footer__icon">
    </a>
    <a href="home.html" class="footer__link">
        <img src="/img/icons/footer-qr.svg" alt="QR код" class="footer__icon">
    </a>
    <a href="profile.html" class="footer__link">
        <img src="/img/icons/footer-user.svg" alt="Профиль" class="footer__icon">
    </a>
</footer>
</body>
</html>
