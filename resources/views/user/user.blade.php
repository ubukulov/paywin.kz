<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.min.css">

    @stack('user_styles')
</head>
<body>
<div class="container">
    <div class="profile">

        @include('user.partials.menu')

        @yield('content')

        <div class="profile__footer">
            <div class="profile__footer-wrapper">
                <a href="{{ route('prizes') }}" class="profile__prizes">
                    <img src="/images/profile/footer-prizes.svg" alt="icon">
                </a>
                <a href="{{ route('home') }}" class="profile__scanner">
                    <div class="profile__scanner-border">
                        <img src="/images/profile/footer-scanner.svg" alt="icon">
                    </div>
                </a>
                <a href="{{ route('user.cabinet') }}" class="profile__profile">
                    <img class="profile__profile-active" src="/images/profile/active-line.svg" alt="" width="46">
                    <img src="/images/profile/profile.svg" alt="icon">
                </a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/3a79d193bf.js"></script>
<script src="/js/profile.js"></script>

@stack('user_scripts')
</body>
</html>
