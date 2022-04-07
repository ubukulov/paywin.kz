@extends('layouts.auth')
@section('content')
    <h1 class="h1 animate__animated animate__fadeInLeft">Изменение пароля</h1>
    <form method="" echo="" id="changePasswordForm" class="change-password-page__inputs changePasswordForm">
        <label for="old-password">
            <p class="label__text animate__animated animate__fadeInLeft">Текущий пароль</p>
            <input id="oldPassword" type="password" placeholder="123456" name="oldPassword" required="" class="change-password-page__input change-password-page__input--old oldPassword">
        </label>

        <label for="new-password">
            <p class="label__text animate__animated animate__fadeInLeft">Новый пароль</p>
            <input id="newPassword" type="password" placeholder="123456" name="newPassword" required="" class="change-password-page__input change-password-page__input--new newPassword">
            <p class="change-password-page__error change-password-page__error--new animate__animated animate__fadeIn">Cлишком простой</p>
        </label>

        <label for="confirm-password">
            <p class="label__text animate__animated animate__fadeInLeft">Подтвердите пароль</p>
            <input id="confirmPassword" type="password" placeholder="123456" name="confirmPassword" required="" class="change-password-page__input change-password-page__input--confirm confirmPassword">
            <p class="change-password-page__error change-password-page__error--confirm animate__animated animate__fadeIn">Пароли не совпадают</p>
        </label>

        <div class="change-password-page__buttons animate__animated animate__fadeInUp">
            <button class="button button--back">вернуться</button>
            <button type="submit" class="button button--go">изменить</button>
        </div>
    </form>
@stop
