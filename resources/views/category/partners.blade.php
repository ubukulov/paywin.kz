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
            @php
                $cashback = $partner->getCashbackSizeAndAmount();
                $shares = $partner->shares;
            @endphp



            <div class="prize prize--1 prizes__item">
                <div class="company prize__company">
                    <img @if(empty($partner->logo)) src="/images/cabinet/papa-johns-pizza.svg" @else src="{{ $partner->logo }}" @endif alt="{{ $partner->company }}" class="company__logo">
                    <h2 class="company__title">{{ $partner->company }}</h2>
                </div>

                <div class="prize__info">
                    <p class="prize__text">Призов: <b>{{ $shares->sum('cnt') }}<b><br>Заказ от: <b>{{ $shares->min('from_order') }}₸</b></p>
                    <div class="prize__slider">

                        @if($cashback && count($cashback) > 0)
                        <div class="slider__item">
                            <p class="slide__text">Cashback {{ $cashback->size }}%<br>при заказе от {{ $cashback->from_order }}₸</p>
                        </div>
                        @endif

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
