@extends('layouts.app')
@section('content')
    <div class="container">
        <form class="payment-page__form" method="post" action="{{ route('payment') }}">
            @csrf
            <div class="actions">
                <div class="action action--card actions__action">
                    <h2 class="offer animate__animated animate__fadeIn">Сумма оплаты</h2>
                    <p class="payment-page__number">
                        <input style="width: 100%;" required type="text" name="sum">
                    </p>
                    <h3 class="action__subtitle">Метод оплаты</h3>
                    <div class="action__flex">
                        <img src="/img/logotypes/mastercard.svg" alt="mastercard" class="action__icon action__icon--card">
                        <p class="action__number action__number--dots">....</p>
                        <p class="action__number">2458</p>
                        <p class="action__warning">-12250</p>
                        <button class="action__button">
                            <img src="/img/icons/arrow-down.svg" alt="Сменить способ оплаты">
                        </button>
                    </div>
                </div>
                <div class="action action--wallet actions__action">
                    <h3 class="action__subtitle">Потратить баланс</h3>
                    <div class="action__flex">
                        <img src="/img/icons/wallet.svg" alt="кошёлек" class="action__icon action__icon--wallet">
                        <p class="action__number">250₸</p>
                        <p class="action__warning">-250</p>
                        <button class="switch-btn action__button action__button--checkbox"></button>
                    </div>
                </div>
                <div class="action action--precent actions__action">
                    <h3 class="action__subtitle">Применить скидку</h3>
                    <div class="action__flex">
                        <img src="/img/icons/precent.svg" alt="проценты" class="action__icon action__icon--precent">
                        <p class="action__number">-50%</p>
                        <p class="action__warning">-6125</p>
                        <button class="switch-btn action__button action__button--checkbox switch-on"></button>
                    </div>
                </div>

                {{--<button class="button button--back">вернуться</button>--}}
                <button type="submit" class="button button--green">оплатить</button>
            </div>


        </form>
    </div>
@stop
