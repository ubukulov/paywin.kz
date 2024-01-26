@extends('user.user')
@section('content')
    <div class="earn">
        <div class="earn__wrapper">
            <div class="earn__item">
                <div class="earn__promocode">newyear2018</div>
                <div class="earn__promocode-info">
                    <p>Активировали: <span>10000</span></p>
                    <p>Поделились: <span>7500</span></p>
                    <p>Доход: <span>2500</span></p>
                </div>
                <a href="#" class="earn__share">
                    <img src="{{ asset('images/profile/share.svg') }}" alt="">
                    поделиться
                </a>
                <a href="#" class="earn__download-banner">
                    <img src="{{ asset('images/profile/download-banner.svg') }}" alt="">
                    скачать баннер
                </a>
                <div class="earn__text">
                    Делитесь своим промо кодом с друзьями, они получат при регистрации бонусные 200тг на счет. Вы будете получать 1% с каждой покупки своего друга
                </div>
            </div>
            <div class="earn__item">
                <div class="earn__promocode">new8</div>
                <div class="earn__promocode-info">
                    <p>Активировали: <span>10000</span></p>
                    <p>Поделились: <span>7500</span></p>
                    <p>Доход: <span>2500</span></p>
                </div>
                <a href="#" class="earn__share">
                    <img src="{{ asset('images/profile/share.svg') }}" alt="">
                    поделиться
                </a>
                <a href="#" class="earn__download-banner">
                    <img src="{{ asset('images/profile/download-banner.svg') }}" alt="">
                    скачать баннер
                </a>
                <div class="earn__text">
                    Делитесь промо кодом с партнерами или регистрируйте их по своему промо коду и зарабатывайте до 30% с прибыли приложения по партнеру
                </div>
            </div>
        </div>
    </div>
@stop
