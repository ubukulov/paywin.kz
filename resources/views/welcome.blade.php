<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paywin.kz - призы за покупки!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/fix.css') }}">
</head>
<body>
<div class="container" style="margin: 25% auto;">
    <div class="profile">
        <div class="row">
            <div class="col-md-12">
                <div class="logo text-center">
                    <img class="logo" src="{{ asset('images/logo_bg_white.jpg') }}" alt="">
                </div>

                <br><br>

                <p style="text-align: center"><strong>О нас:</strong></p>
                <p style="text-align: center;">

                    Дарим покупателям подарки и положительные эмоции за каждую покупку среди десятка наших партнеров !
                </p>

                <br><br>
            </div>
        </div>

        <div class="row auth-btns">
            <div class="col text-right">
                <a class="btn btn-warning" href="{{ route('login') }}">Авторизоваться</a>
            </div>

            <div class="col">
                <a href="{{ route('register') }}" class="btn btn-warning">Регистрация</a>
            </div>
        </div>
    </div>

    @include('_partials.info')
</div>
</body>
</html>
