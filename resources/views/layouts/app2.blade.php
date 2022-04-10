<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Призы</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/payment.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicons/favicon-16x16.png">
    <link rel="manifest" href="img/favicons/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body class="container prizes-page">

@yield('content')

<footer class="footer container animate__animated animate__fadeInUp">
    <a href="{{ route('prizes') }}" class="footer__link">
        <img src="img/icons/footer-gift-active.svg" alt="Подарок" class="footer__icon">
    </a>
    <a href="{{ route('home') }}" class="footer__link">
        <img src="img/icons/footer-qr-grey.svg" alt="QR код" class="footer__icon">
    </a>
    <a @if(Auth::user()->user_type == 'partner') href="{{ route('partner.cabinet') }}" @else href="{{ route('user.cabinet') }}" @endif class="footer__link">
        <img src="/img/icons/footer-user.svg" alt="Профиль" class="footer__icon">
    </a>
</footer>
</body>
</html>
