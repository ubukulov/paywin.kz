<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Не дали приз</title>
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/style.min.css">
</head>
<body>

<div class="container">
    <div class="not-given-prize">
        <div class="review__title"><span></span> ОСТАВИТЬ ОТЗЫВ</div>
        <div class="review__header">
            <div class="review__img"><img src="/images/review/Bitmap.png" alt=""></div>
            <div class="review__header-item">
                <div class="review__header-title">KFC</div>
                <div class="review__header-inner">
                    <div class="review__header-inner-block">
                        <div class="review__header-subtitle">Текущий рейтинг</div>
                        <div class="review__header-star">
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star star"></i>
                            <i class="fas fa-star empty"></i>
                            <i class="fas fa-star empty"></i>
                        </div>
                    </div>
                    <div class="review__header-rating">3.2</div>
                </div>
                <a href="#" class="review__header-link">на основе 443 отзывов</a>
            </div>
        </div>
        <div class="review__main">
            <div class="review__main-title">
                Напишите пожалуйста, по какой причине вы не получили
                приз от партнера. и мы постараемся максимально быстро
                решить ваш впрос
            </div>
            <form action="/" class="review__main-form">
                <textarea class="review__form-textarea" name="review-textarea" class="review__main-textarea" placeholder="Напишите комментарий"></textarea>
                <button type="submit" class="review__form-btn">продолжить <img src="/images/right-arrow.svg" alt="icon"></button>
            </form>
            <div class="review__main-subtitle">
                или свяжитесь с нами любым удобным способом <br>
                +77759931157
            </div>
            <div class="review__social">
                <a href="https://t.me/+77759931157" class="review__link"><img src="/images/telegram.svg" alt="icon"></a>
                <a href="tel:+77759931157" class="review__link"><img src="/images/phone.svg" alt="icon"></a>
                <a href="https://wa.me/+77759931157" class="review__link"><img src="/images/whatsApp.svg" alt="icon"></a>
            </div>
        </div>
        <div class="review__footer">
            <div class="review__footer-wrapper">
                <a href="{{ route('prizes') }}" class="review__prizes">
                    <img src="/images/review/prizes.svg" alt="icon">
                </a>
                <a href="{{ route('home') }}" class="review__scanner">
                    <div class="review__scanner-border">
                        <img src="/images/review/scanner.svg" alt="icon">
                    </div>
                </a>
                <a @if(Auth::user()->user_type == 'partner') href="{{ route('partner.cabinet') }}" @else href="{{ route('user.cabinet') }}" @endif class="review__profile">
                    <img src="/images/review/profile.svg" alt="icon">
                </a>
            </div>
        </div>
    </div>
</div>
<script src="/js/main.min.js"></script>
</body>
</html>
