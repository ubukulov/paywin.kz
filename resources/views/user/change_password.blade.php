<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль настройки</title>
    <link rel="stylesheet" href="/css/style.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="max-width: 480px !important;">
    <div class="settings">
        <a href="{{ route('user.settings') }}" class="settings__back-btn"><img src="/images/profile/back-btn.svg" alt="">НАЗАД</a>

        <div class="settings__wrapper">
            <form action="{{ route('user.setting.passwordUpdate') }}" method="post">
                @csrf

                <div class="form-group">
                    <label>Текущий пароль</label>
                    <input type="password" name="password" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Новый пароль</label>
                    <input type="password" name="new_password" required class="form-control">
                </div>

                <div class="form-group">
                    <label>Подтвердите новый пароль</label>
                    <input type="password" name="confirm_new_password" required class="form-control">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Изменить</button>
                </div>
            </form>
        </div>
    </div>

    {{--<div class="settings__footer" style="max-width: 480px; margin: 0 auto;">
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
</div>

<script src="/js/profile.js"></script>
</body>
</html>
