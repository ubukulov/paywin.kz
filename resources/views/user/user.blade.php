<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link rel="stylesheet" href="/css/style.min.css">
    <link rel="stylesheet" href="/css/fix.css">
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('user_styles')
</head>
<body>
<div class="container">
    <div class="profile">

        @include('user.partials.menu')

        <div id="content">
            @yield('content')
        </div>

        <div class="">
            <div class="profile__footer-wrapper">
                <a href="{{ route('prizes') }}" class="profile__prizes">
                    <img src="/images/profile/footer-prizes.svg" alt="icon">
                </a>
                <a href="{{ route('home') }}" class="footer__link">
                    <img src="/img/icons/footer-qr.svg" alt="QR код" class="footer__icon">
                </a>
                <a href="{{ route('user.cabinet') }}" class="profile__profile">
                    <img class="profile__profile-active" src="/images/profile/active-line.svg" alt="" width="46">
                    <img src="/images/profile/profile.svg" alt="icon">
                </a>
            </div>
        </div>

        @include('_partials.info')
    </div>
</div>
{{--<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/3a79d193bf.js"></script>
<script src="/js/profile.js"></script>--}}
<script>
    const btn = document.getElementById('burgerBtn')
    const menu = document.getElementById('mobileMenu')

    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden')
    })
</script>
@stack('user_scripts')
</body>
</html>
