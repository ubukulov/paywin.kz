@extends('layouts.auth')
@section('title')
    Авторизация
@endsection
@section('content')
    <h1 class="h1 animate__animated animate__fadeInLeft">Авторизация</h1>
    <h2 class="offer animate__animated animate__fadeIn">Войдите, используя номер телефона</h2>
    <form method="post" action="{{ route('authenticate') }}" class="auth-page__inputs">
        @csrf
        <label for="auth__phone-input">
            <p class="label__text animate__animated animate__fadeInLeft">Номер телефона</p>
            <input id="auth-phone-input" type="tel" required placeholder="Введите номер телефона…" name="phone" class="input reg-1__input--phone">
        </label>

        <label for="auth__password-input">
            <p class="label__text animate__animated animate__fadeInLeft">Пароль</p>
            <input id="auth__password-input" type="password" required placeholder="" name="password" class="input reg-1__input--code">
        </label>

        <div class="auth__buttons animate__animated animate__fadeInUp">
            <a href="{{ route('register') }}">Регистрация</a>
            <button type="submit" class="button button--go">Войти</button>
        </div>
    </form>
@stop

@push('js')
    <script>
        $(document).ready(function(){
            $('#auth-phone-input').mask('+7 999-999-99-99');
        });
    </script>
@endpush
