@extends('user.user')
@section('content')
    <div class="profile__wrapper">
        <div class="profile__left">
            <div class="profile__avatar">
                <img src="/images/profile/avatar.png" alt="">
            </div>
            <div class="profile__balance">
                <img src="/images/profile/wallet.svg" alt="">
                <p class="profile__balance-sum">на счету <br> <span>250 ₸</span></p>
                <a href="#" class="profile__balance-replenish">+</a>
            </div>
            <p class="profile__bonus">+<span>350</span> бонусов</p>
            <div class="profile__bank-card-block">
                <div class="profile__bank-card-title">Банковская карта</div>
                {{--<div class="profile__bank-card">
                    <div class="profile__bank-card-number"><span>****</span><span>****</span><span>****</span><span class="profile__bank-card-lastnumbers">9981</span></div>
                    <div class="profile__bank-card-logo"><img src="/images/profile/card-logo.svg" alt="card-logo"></div>
                </div>--}}
                <div class="action__flex">

                    <select name="card_id" style="font-size: 14px;border-color: #ccc;border-radius: 5px;color: green;">
                        @foreach($user->getMyCards() as $card)
                            <option value="{{ $card['id'] }}">{{ $card['number'] }}</option>
                        @endforeach
                    </select>
                </div>


                <a href="{{ route('user.addMyCard') }}" class="profile__bank-card-upload">+ прикрепить</a>
            </div>
        </div>
        <div class="profile__right">
            <a href="{{ route('user.settings') }}" class="profile__settings">
                <img src="/images/profile/settings.svg" alt="">
            </a>
            <div class="profile__username">
                <img src="/images/profile/user-icon.svg" alt="">
                <span>Sasha Grey</span>
            </div>
            <div class="profile__sex">
                <img src="/images/profile/female.svg" alt="">
                <span>Женский</span>
            </div>
            <div class="profile__sex">
                <img src="/images/profile/male-icon.svg" alt="">
                <span>Мужской</span>
            </div>
            <div class="profile__birthdate">
                <img src="/images/profile/birthday-cake.svg" alt="">
                <span>1 сентября 1989г</span>
            </div>
            <div class="profile__telephone-number">
                <img src="/images/profile/mobile-phone.svg" alt="">
                <span>+7(999)999-99-99</span>
            </div>
            <div class="profile__email">
                <img src="/images/profile/mail.svg" alt="">
                <span>d1one7@ya.ru</span>
            </div>
            <p class="profile__active-promocode">Активировать промокод</p>
            <div class="profile__promocode-input">
                <input type="text" placeholder="Введите промокод">
                <a href="#" class="profile__promocode-scanner">
                    <img src="/images/profile/scanner-promocode-icon.svg" alt="scanner">
                </a>
                <button class="profile__promocode-btn" type="submit">OK</button>
            </div>
            <div class="profile__surprize">
                <div class="profile__surprize-logo">
                    <img src="/images/profile/logo.png" alt="logo">
                </div>
                <div>
                    <div class="profile__surprize-info">
                        Подарок от <br> <span class="profile__surprize-company">Papa John’s</span> <br> <span class="profile__surprize-sum">+350тг</span>
                    </div>
                    <a href="#" class="profile__surprize-btn">
                        <img src="/images/profile/check.svg" alt="">
                        получить
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="profile__info">
        Прикрепи свою банковскую карту для быстрой и удобной оплаты Не беспокойся, твои данные под надежной защитой!
    </div>
    <a href="#" class="profile__user-agreement">Пользовательское соглашение</a>
@stop
