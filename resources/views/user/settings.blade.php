<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль настройки</title>
    <link rel="stylesheet" href="/css/style.min.css">
</head>
<body>
<div class="container">
    <div class="settings">
        <a href="{{ route('user.cabinet') }}" class="settings__back-btn"><img src="/images/profile/back-btn.svg" alt="">НАЗАД</a>
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

    {{--<div class="settings__footer">
        <div class="settings__footer-wrapper">
            <a href="{{ route('prizes') }}" class="settings__prizes">
                <img src="/images/profile/footer-prizes.svg" alt="icon">
            </a>
            <a href="{{ route('home') }}" class="settings__scanner">
                <div class="settings__scanner-border">
                    <img src="/images/profile/footer-scanner.svg" alt="icon">
                </div>
            </a>
            <a href="{{ route('user.cabinet') }}" class="settings__footer-profile">
                <img class="settings__footer-profile-active" src="/images/profile/active-line.svg" alt="" width="46">
                <img src="/images/profile/profile.svg" alt="icon">
            </a>
        </div>
    </div>--}}

<!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('user.balanceReplenishment') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Пополнение</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="amount" placeholder="Введите сумму" required class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="submit" class="btn btn-primary">Продолжить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="/js/profile.js"></script>
</body>
</html>
