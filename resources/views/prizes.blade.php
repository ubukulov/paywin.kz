@extends('layouts.app2')
@section('content')
    <header class="header prizes-page__header"><a href="#" class="header__categories">
            <img src="img/icons/header-categories.svg" alt="Открыть меню"> </a>
        <button class="switch-btn switch-on"></button>
        <img src="img/icons/header-location.svg" alt="Местоположение" class="header__geo">
        <nav class="nav header__nav">
            <a href="#" class="nav__link nav__link--active">Призы</a>
            <a href="#" class="nav__link">Победители</a>
            <a href="#" class="nav__link">Мои призы</a>
        </nav>
    </header>
    <main>

        @foreach($prizes as $prize)
            @php
                $partner = \App\Models\User::find($prize->share->user_id);
                $partner_profile = $partner->profile;
            @endphp
        <div class="prize prize--1 prizes__item">
            <div class="company prize__company">
                <img @if(empty($partner_profile->logo)) src="/img/logotypes/papa-johson.png" @else src="{{ $partner_profile->logo }}" @endif alt="{{ $partner_profile->company }}" class="company__logo">
                <h2 class="company__title">{{ $partner_profile->company }}</h2>
            </div>
            <div class="prize__info">
                <p class="prize__text">Призы: <b>{{ $prize->cnt }}</b><br>Заказ от: <b>{{ $prize->share->from_order }}₸</b></p>
                <div class="prize__slider">
                    <div class="slider__item">
                        <p class="slide__text">Cashback 50%<br>при заказе от 6000₸</p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </main>
@stop
