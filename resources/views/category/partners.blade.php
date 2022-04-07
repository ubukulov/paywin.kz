@extends('layouts.app')
@section('content')
    <style>
        .review__form-btn{
            display: block;
            margin: 30px auto 0;
            background: #FD9B11;
            border: none;
            border-radius: 28px;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            line-height: 20px;
            -webkit-box-shadow: #FD9B11 28px;
            box-shadow: #FD9B11 28px;
            padding: 6px 55px 8px 42px;
            cursor: pointer;
            -webkit-box-shadow: 0px 0px 23px #fd9b11;
            box-shadow: 0px 0px 23px #fd9b11;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-column-gap: 7.5px;
            -moz-column-gap: 7.5px;
            column-gap: 7.5px;
        }
    </style>
    <div class="container prizes-page">
        <main>
            @foreach($partners as $partner)
            <div class="prize prize--1 prizes__item">
                <div class="company prize__company">
                    <img src="/img/logotypes/papa-johson.png" alt="Papa John`s" class="company__logo">
                    <h2 class="company__title">{{ $partner->company }}</h2>
                </div>
                <div class="prize__info">
                    <p class="prize__text">Призы: <b>10</b><br>Заказ от: <b>6000₸</b></p>
                    <div class="prize__slider">
                        <div class="slider__item">
                            <p class="slide__text">Cashback 50%<br>при заказе от 6000₸</p>
                        </div>

                        <div>
                            <a href="{{ route('showPartner', ['slug' => $slug, 'id' => $partner->user_id]) }}" class="review__form-btn btn btn-success">подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </main>
    </div>
@endsection
