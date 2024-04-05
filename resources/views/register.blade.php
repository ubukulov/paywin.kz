@extends('layouts.auth')
@section('title')
    Регистрация
@endsection
@section('content')
    <h1 class="h1 animate__animated animate__fadeInLeft">Регистрация</h1>
    <h2 class="offer animate__animated animate__fadeIn">Зарегистрируйтесь, используя номер телефона</h2>
    <form action="{{ route('registration') }}" method="post" class="registration-1-page__inputs">
        @csrf
        <label for="reg-1-phone-input">
            <p class="label__text animate__animated animate__fadeInLeft">Номер телефона</p>
            <input id="reg-1-phone-input" type="tel" required placeholder="Введите номер телефона…" name="phone" class="input reg-1__input--phone">
        </label>

        <label for="code-input">
            <p class="label__text animate__animated animate__fadeInLeft">Промокод</p>
            <input id="code-input" type="text" placeholder="Введите промокод (необязательно)" name="code" class="input reg-1__input--code">
        </label>

        <label for="reg-1-partner" class="reg-1__label--partner animate__animated animate__fadeIn">
            <input id="reg-1-partner" type="checkbox" name="partner" value="yes" checked="" class="checkbox reg-1__input--partner custom-checkbox">
            <label for="reg-1-partner"></label>
            <p class="label__text">Стать партнером</p>
        </label>

        <label for="reg-2-partner" class="reg-1__label--partner animate__animated animate__fadeIn">
            <input id="reg-2-partner" type="checkbox" readonly name="afferts" value="yes" checked="" class="checkbox reg-1__input--partner custom-checkbox">
            <label for="reg-2-partner"></label>
            <p class="label__text"> Подтверждаю ознакомление и согласие с условиями <a href="{{ asset('files/публичная_оферта.pdf') }}" target="_blank">Публичной оферты</a></p>
        </label>

        <div class="registration-1-page__buttons animate__animated animate__fadeInUp">
            <a href="{{ route('login') }}">Авторизация</a>
            <button type="submit" style="width: 235px !important;" class="button button--go">Зарегистрироваться</button>
        </div>
    </form>
@stop

@push('js')
    <script>
        $(document).ready(function(){
            $('#reg-1-phone-input').mask('+7 999-999-99-99');
        });
    </script>
@endpush
