<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Как это работает</title>
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.min.css">
</head>
<body>

<div class="container">
    <div class="hiworks">
        <div class="hiworks__title">Как это <br> работает</div>
        <div class="hiworks__wrapper">
            <div class="hiworks__item">
                <div class="hiworks__icon">
                    <img src="images/hiworks/phone.svg" alt="">
                </div>
                <div class="hiworks__text">
                    Сделай заказ <br> и отсканируй QR код
                </div>
            </div>
            <div class="hiworks__item">
                <div class="hiworks__icon">
                    <img src="images/hiworks/card.svg" alt="">
                </div>
                <div class="hiworks__text">
                    Оплати счёт <br> с баланса или карты
                </div>
            </div>
            <div class="hiworks__item">
                <div class="hiworks__icon">
                    <img src="images/hiworks/winning-present.svg" alt="">
                </div>
                <div class="hiworks__text">
                    Выиграй у партнёра <br> крутой подарок
                </div>
            </div>
        </div>
        <a href="{{ route('home') }}" class="hiworks__back-btn">
            <img src="images/hiworks/back-arrow-btn.svg" alt="">
            вернуться
        </a>
        {{--<div class="hiworks__footer">
            <div class="hiworks__footer-wrapper">
                <a href="#" class="hiworks__prizes">
                    <img src="images/hiworks/prizes.svg" alt="icon">
                </a>
                <a href="#" class="hiworks__scanner">
                    <div class="hiworks__scanner-border">
                        <img src="images/hiworks/scanner.svg" alt="icon">
                    </div>
                </a>
                <a href="#" class="hiworks__profile">
                    <img src="images/hiworks/profile.svg" alt="icon">
                </a>
            </div>
        </div>--}}
    </div>
</div>
</body>
</html>
