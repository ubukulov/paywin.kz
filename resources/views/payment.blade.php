<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Оплата</title>
    <link rel="stylesheet" href="/b5/css/style.css">
    <script src="/b5/js/jquery-3.6.0.min.js"></script>
    <script src="/b5/js/script.js"></script>
    <script src="/b5/js/slick.min.js"></script>
    <script src="/b5/js/payment.js"></script>
    <link rel="apple-touch-icon" sizes="180x180" href="/b5/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/b5/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/b5/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="/b5/img/favicons/site.webmanifest">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script src="https://widget.tiptoppay.kz/bundles/widget.js"></script>
    <style>
        .hidden {
            display: none;
        }
        .form-check-input:checked{
            background-color: #18BE1E !important;
            border-color: #18BE1E !important;
        }
        .form-switch .form-check-input{
            font-size: 20px;
        }
        .actions{
            width: 380px !important;
        }
        .action__flex {
            justify-content: space-between;
        }
        .action__warning {
            position: relative;
            top: 0px;
            right: 0px;
            font-size: 18px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="container payment-page">
<h1 class="h1 animate__animated animate__fadeInLeft">Оплата</h1>
<br>
@php
    $profile = $partner->profile;
@endphp

<div style="display: flex; justify-content: center;" class="partdescr__profile">
    <div style="margin-right: 20px;" class="partdescr__profile-logo">
        <img @if(empty($profile->logo)) src="/images/partner-description/partner-logo.png" @else src="{{ $profile->logo }}" @endif alt="logo">
    </div>
    <div class="partdescr__profile-block">
        <div style="font-weight: bold;" class="partdescr__profile-name">{{ $profile->company }}</div>
        <div style="color: #9B9B9B;" class="partdescr__profile-descr">{{ $profile->category->title }}</div>
    </div>
</div>
<br>
    @csrf
    <input type="hidden" id="partner_id" name="partner_id" value="{{ $id }}">
    <div class="actions">
        <h2 style="margin-top: 0px;" class="offer animate__animated animate__fadeIn">Сумма оплаты</h2>
        <p class="payment-page__number">
            <input id="payment_input" style="width: 200px;
            max-width: 100%;
            height: 35px;
            border-radius: 5px;
            border: 1px solid #ccc; text-align: center;" required type="text" name="sum">
        </p>

        @if($cards = $user->getMyCards())
        <div class="action action--card actions__action">
            <h3 class="action__subtitle">Метод оплаты</h3>
            @include('_partials._payment_cards', $cards)
        </div>
        @endif

        @if($user_balance = $user->getBalanceForUser())
        <div class="action action--wallet actions__action">
            <h3 class="action__subtitle">Потратить баланс</h3>
            <div class="action__flex">
                <img src="/b5/img/icons/wallet.svg" alt="кошёлек" class="action__icon action__icon--wallet">
                <p id="balance" class="action__number">{{ $user_balance }} ₸</p>
{{--                <p class="action__warning">-250</p>--}}
{{--                <button id="balance_switch" class="switch-btn action__button action__button--checkbox"></button>--}}
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                </div>
            </div>
            <input type="hidden" id="balance_hidden" name="balance" value="0">
        </div>
        @endif

        @if($user_discount = $user->getDiscountForUser())
        <div class="action action--precent actions__action">
            <h3 class="action__subtitle">Применить скидку</h3>
            <div class="action__flex">
                <img src="/b5/img/icons/precent.svg" alt="проценты" class="action__icon action__icon--precent">
                <p class="action__number"><span id="discount">{{ $user_discount->size }}%</span><span id="discount_max_sum" data-sum="{{ $user_discount->max_sum }}" style="font-size: 12px;"> (до {{ $user_discount->max_sum }} тг)</span></p>

{{--                <button class="switch-btn switch-on action__button action__button--checkbox"></button>--}}
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="discount_input">
                </div>
            </div>
            <input type="hidden" id="discount_hidden" name="discount" value="0">
        </div>
        @endif

        <hr>

        <div id="to_payment" class="to_payment hidden">
            <h3 style="margin-top: 0px;" class="offer animate__animated animate__fadeIn">К оплате</h3>

            <div id="balance_size_hidden" class="action action--wallet actions__action hidden">
                <h3 class="action__subtitle">С баланса</h3>
                <div class="action__flex">
                    <img src="/b5/img/icons/wallet.svg" alt="кошёлек" class="action__icon action__icon--wallet">
                    <p class="action__number"></p>
                    <p id="balance_size" class="action__warning"></p>
                </div>
            </div>

            <div id="discount_size_hidden" class="action action--precent actions__action hidden">
                <h3 class="action__subtitle">Скидка</h3>
                <div class="action__flex">
                    <img src="/b5/img/icons/precent.svg" alt="проценты" class="action__icon action__icon--precent">
                    <p class="action__number"></p>
                    <p id="discount_size" class="action__warning"></p>
                </div>
            </div>

            <div class="action action--precent actions__action" style="margin-bottom: 20px;">
                <h3 class="action__subtitle">Итоговая сумма</h3>
                <div class="action__flex">
                    <img src="/b5/img/icons/wallet.svg" alt="кошёлек" class="action__icon action__icon--wallet">
                    <p class="action__number"></p>
                    <p id="sum_amount" class="action__warning"></p>
                    <input type="hidden" id="sum_amount_hidden" name="sum_amount" value="0">
                </div>
            </div>
        </div>

    </div>

    <div class="payment-page__slider-buttons animate__animated animate__fadeIn">
        <div>
            <h2 class="slider__title">Выиграйте один из призов</h2>
            <div class="slider">

                @foreach($partner->shares as $share)
                <div class="slide slider__item">
                    <p class="slide__text">{{ \Illuminate\Support\Str::limit($share->title, 14) }}<br>до {{ $share->to_order }}₸</p>
                </div>
                @endforeach

            </div>
        </div>
        <div class="payment-page__buttons">
            <button type="button" id="paymentBtn" class="button button--green">оплатить</button>
            <a href="{{ route('showPartner', ['slug' => $slug, 'id' => $id]) }}" class="button button--back">
                вернуться
            </a>
        </div>
    </div>

<footer class="footer container animate__animated animate__fadeInUp">
    <a href="{{ route('prizes') }}" class="footer__link">
        <img src="/b5/img/icons/footer-gift.svg" alt="Подарок" class="footer__icon">
    </a>
    <a href="{{ route('home') }}" class="footer__link">
        <img src="/b5/img/icons/footer-qr.svg" alt="QR код" class="footer__icon">
    </a>
    <a @if(Auth::user()->user_type == 'partner') href="{{ route('partner.cabinet') }}" @else href="{{ route('user.cabinet') }}" @endif class="footer__link">
        <img src="/b5/img/icons/footer-user.svg" alt="Профиль" class="footer__icon">
    </a>
</footer>

@include('_partials.info')

<div id="paymentLoader" class="payment-loader">
    <div class="loader-card">
        <div class="loader-circle">
            <span>₸</span>
        </div>
        <p class="loader-title">Обрабатываем платеж</p>
        <p class="loader-subtitle">Пожалуйста, не закрывайте страницу</p>
    </div>
</div>
<style>
    .payment-loader {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.95);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99999;
    }

    .loader-card {
        text-align: center;
        padding: 40px;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 40px rgba(0,0,0,.1);
    }

    .loader-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 4px solid #e5e5e5;
        border-top-color: #18BE1E;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        animation: spin 1.1s linear infinite;
    }

    .loader-circle span {
        font-size: 32px;
        font-weight: bold;
        color: #18BE1E;
    }

    .loader-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .loader-subtitle {
        font-size: 14px;
        color: #8b8b8b;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

</style>
<script>
    $(document).ready(function(){
        let payment_input = $('#payment_input');
        let to_payment    = $('#to_payment');
        let balance    = $('#balance');
        let discount    = $('#discount');
        let balance_switch     = $('#flexSwitchCheckDefault');
        let discount_switch    = $('#discount_input');
        let balance_size       = $('#balance_size');
        let discount_size      = $('#discount_size');
        let sum_amount         = $('#sum_amount');
        let balance_size_hidden         = $('#balance_size_hidden');
        let discount_size_hidden         = $('#discount_size_hidden');
        let balance_hidden         = $('#balance_hidden');
        let discount_hidden         = $('#discount_hidden');
        let sum_amount_hidden         = $('#sum_amount_hidden');
        let discount_max_sum         = $('#discount_max_sum');

        payment_input.on("focusout", function(){
            let amount = payment_input.val();
            if(amount.length > 0) {
                to_payment.removeClass('hidden');
                sum_amount.html(amount + " ₸");
                sum_amount_hidden.val(amount);
                balance_switch.prop('checked', false);
                discount_switch.prop('checked', false);
            }
        });

        balance_switch.click(function(){
            let amount = parseInt(payment_input.val());
            if(balance_switch.prop("checked")) {
                let balance_amount = parseInt(balance.html());
                if(balance_amount >= amount) {
                    balance_size.html("-" + amount);
                    sum_amount.html("0 ₸");
                    sum_amount_hidden.val(0);
                } else {
                    balance_size.html("-" + balance_amount);
                    let d = amount - balance_amount;
                    sum_amount.html(d + " ₸");
                    sum_amount_hidden.val(d);
                }

                if(discount_switch.prop("checked")) {
                    let discount_amount = parseInt(discount.html());
                    let d = ((amount * discount_amount) / 100);
                    let need_amount = amount - Math.abs(d);
                    if(balance_amount >= need_amount) {
                        balance_size.html("-" + need_amount);
                        sum_amount.html("0 ₸");
                        sum_amount_hidden.val(0);
                    } else {
                        let c = need_amount - balance_amount;
                        balance_size.html("-" + balance_amount);
                        sum_amount.html(c + " ₸");
                        sum_amount_hidden.val(c);
                    }
                }

                balance_size_hidden.removeClass('hidden');
                balance_hidden.val(1);
            } else {
                if(discount_switch.prop("checked")) {
                    let discount_amount = parseInt(discount.html());
                    let dd;
                    let d = ((amount * discount_amount) / 100);
                    let max_d = discount_max_sum.data('sum');
                    if(Math.abs(d) >= max_d) {
                        discount_size.html("-" + Math.abs(max_d));
                        dd = max_d;
                    } else {
                        discount_size.html("-" + Math.abs(d));
                        dd = d;
                    }
                    let need_amount = amount - Math.abs(dd);
                    sum_amount.html(need_amount + " ₸");
                    sum_amount_hidden.val(need_amount);
                } else {
                    sum_amount.html(amount + " ₸");
                    sum_amount_hidden.val(amount);
                }

                balance_size_hidden.addClass('hidden');
                balance_size.html('');
                balance_hidden.val(0);
            }
        });

        discount_switch.click(function(){
            let amount = parseInt(payment_input.val());
            if(discount_switch.prop("checked")) {
                let discount_amount = parseInt(discount.html());
                discount_size_hidden.removeClass('hidden');
                let dd;
                let d = ((amount * discount_amount) / 100);
                let max_d = discount_max_sum.data('sum');
                if(Math.abs(d) >= max_d) {
                    discount_size.html("-" + Math.abs(max_d));
                    dd = max_d;
                } else {
                    discount_size.html("-" + Math.abs(d));
                    dd = d;
                }

                discount_hidden.val(1);

                let need_amount = amount - Math.abs(dd);

                if(balance_switch.prop("checked")) {
                    let balance_amount = parseInt(balance.html());
                    if(balance_amount >= need_amount) {
                        balance_size.html("-" + need_amount);
                        balance_hidden.val(1);
                        sum_amount.html("0 ₸");
                        sum_amount_hidden.val(0);
                    } else {
                        let c = need_amount - balance_amount;
                        balance_size.html("-" + balance_amount);
                        balance_hidden.val(1);
                        sum_amount.html(c + " ₸");
                        sum_amount_hidden.val(c);
                    }
                } else {
                    sum_amount.html(need_amount + " ₸");
                    sum_amount_hidden.val(need_amount);
                }
            } else {
                if(balance_switch.prop("checked")) {
                    let balance_amount = parseInt(balance.html());
                    if(balance_amount >= amount) {
                        balance_size.html("-" + amount);
                        sum_amount.html("0 ₸");
                        sum_amount_hidden.val(0);
                    } else {
                        balance_size.html("-" + balance_amount);
                        let d = amount - balance_amount;
                        sum_amount.html(d + " ₸");
                        sum_amount_hidden.val(d);
                    }
                } else {
                    balance_size_hidden.addClass('hidden');
                    balance_size.html('');
                    balance_hidden.val(0);
                    sum_amount.html(amount + " ₸");
                    sum_amount_hidden.val(amount);
                }
                discount_size_hidden.addClass('hidden');
                discount_size.html('');
                discount_hidden.val(0);
            }
        });
    });
</script>

<script>

    const btn = document.getElementById("paymentBtn")
    const loader = document.getElementById("paymentLoader");

    const widget = new tiptop.Widget();

    const launchWidget = () => {
        const amountInput = document.getElementById('sum_amount_hidden').value;
        const partnerId = document.getElementById('partner_id').value;
        const csrf = document.querySelector('input[name="_token"]').value;

        if (!amountInput || isNaN(amountInput) || Number(amountInput) <= 0) {
            alert('Введите корректную сумму');
            return;
        }

        const amountSum = Number(amountInput);

        btn.disabled = true;

        const intentParams = {
            publicTerminalId: "pk_ee882e56bdffee4bea6f8f97290c6",
            paymentSchema: 'Dual',
            currency: "KZT",
            amount: amountSum, // ✅ берем сумму из input
            successRedirectUrl: "https://paywin.kz/success/payment",
            failRedirectUrl: "https://paywin.kz/error/payment",
            tokenize: true,
        };

        widget.start(intentParams)
            .then(result => {
                if (!result?.data?.transactionId) return;

                loader.style.display = 'flex';

                fetch('/payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({
                        transaction_id: result.data.transactionId,
                        amount: amountSum,
                        partner_id: partnerId
                    })
                })
                    .then(() => {
                        loader.style.display = 'none';
                        window.location.href = '/success/payment';
                    })
                    .catch(() => window.location.href= '/error/payment');
            })
            .catch(error => {
                console.error(error);
                btn.disabled = false;
            });
    };

    btn.addEventListener('click', launchWidget)
</script>
</body>
</html>
