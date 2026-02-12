@extends('user.user')

@section('content')
    <div class="settings">
        <div class="settings__profile">
            <div class="settings__profile-avatar">
                <img src="/images/profile/avatar.png" alt="">
            </div>
            <div class="settings__profile-content">
                <div class="settings__profile-username">{{ $user_profile->full_name }}</div>


                <div class="settings__profile-sex">
                    @if($user_profile->sex == 1)
                        <img src="/images/profile/male-icon.svg" alt="">
                        Мужской
                    @elseif($user_profile->sex == 2)
                        <img src="/images/profile/female.svg" alt="">
                        Женский
                    @endif


                </div>

                <div class="settings__profile-balance">
                    Баланс: <span class="settings__balance-sum">{{ Auth::user()->getBalanceForUser() }} ₸</span> {{--<span class="settings__balance-bonus">+350 бонусов</span>--}}
                </div>
                {{--                <a href="#" class="settings__profile-replenish">+ пополнить</a>--}}
                <button type="button" class="settings__profile-replenish" data-toggle="modal" data-target="#exampleModal">+ пополнить</button>
                {{--                <a href="#" class="settings__profile-output">- вывести</a>--}}
            </div>
        </div>
        <div class="settings__wrapper">
            {{--<a href="#" class="settings__card">
                <div class="settings__card-block">
                    <img src="/images/profile/master-card.svg" alt="">
                    <span class="settings__card-dot"></span>
                    <span class="settings__card-dot"></span>
                    <span class="settings__card-dot"></span>
                    <span class="settings__card-dot"></span>
                    <span class="settings__card-last-num">2458</span>
                </div>
                <img src="/images/profile/right-arrow.svg" alt="">
            </a>
            <a href="#" class="settings__share-app">Поделиться приложением <img src="/images/profile/right-arrow.svg" alt=""></a>--}}
            <p>Безопасность</p>
            {{--<a href="#" class="settings__code">Код доступа <img src="/images/profile/right-arrow.svg" alt=""></a>--}}
            <a href="{{ route('user.setting.passwordChangeForm') }}" class="settings__change-pw">Сменить пароль <img src="/images/profile/right-arrow.svg" alt=""></a>
            <p>Информация</p>
            {{--<a href="#" class="settings__contact-us">Связаться с нами <img src="/images/profile/right-arrow.svg" alt=""></a>--}}
            <a href="{{ route('user.setting.profile') }}" class="settings__about-us">Профиль <img src="/images/profile/right-arrow.svg" alt=""></a>
            {{--<a href="#" class="settings__biznes">Бизнесу <img src="/images/profile/right-arrow.svg" alt=""></a>
            <a href="#" class="settings__franshiza">Франшиза <img src="/images/profile/right-arrow.svg" alt=""></a>
            <a href="#" class="settings__about-app">О приложении <img src="/images/profile/right-arrow.svg" alt=""></a>--}}
            <a href="{{ route('logout') }}" class="settings__quit-accaunt">Выйти из аккаунта <img src="/images/profile/right-arrow.svg" alt=""></a>
        </div>
    </div>
@endsection
