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
            <form action="{{ route('user.setting.profileUpdate') }}" method="post">
                @csrf
                <div class="form-group">
                    <label>ФИО</label>
                    <input type="text" value="{{ $user_profile->full_name }}" name="full_name" class="form-control">
                </div>

                <div class="form-group">
                    <label>Пол</label>
                    <select name="sex" class="form-control">
                        <option @if($user_profile->sex == 1) selected @endif value="1">Мужской</option>
                        <option @if($user_profile->sex == 2) selected @endif value="2">Женский</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Дата рождения</label>
                    <input type="date" value="{{ $user_profile->birth_date }}" name="birth_date" class="form-control">
                </div>

                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" value="{{ $user_profile->phone }}" name="phone" class="form-control">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" value="{{ $user_profile->email }}" name="email" class="form-control">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
    <div class="settings__footer" style="max-width: 480px; margin: 0 auto;">
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
    </div>
</div>

<script src="/js/profile.js"></script>
</body>
</html>
