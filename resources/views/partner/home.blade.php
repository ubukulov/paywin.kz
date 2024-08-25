@extends('partner.partner')
@section('content')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/css/style.min.css">
    <div class="partner">
        <div class="partner__header-block">
            <div class="partner__left">
                <div class="partner__logo">
                    <img @if(empty($user_profile->logo)) src="/images/cabinet/papa-johns-pizza.svg" @else src="{{ $user_profile->logo }}" @endif alt="logo">
                </div>
                <div class="partner__persent-block">
                    <div class="partner__persent-img"><img src="/images/cabinet/persent-icon.svg" alt="icon"></div>
                    <div class="partner__persent-text">тариф <br> <span>{{$user_profile->percent}}%</span></div>
                </div>
            </div>
            <div class="partner__right">
                <div class="partner__name">{{ $user_profile->company }}</div>
                <div class="partner__right-item partner__right-wallet">
                    <img src="/images/cabinet/wallet.svg" alt="icon">
                    <div class="partner__right-wallet-text">на счету <br> <span>{{ $partner->getBalance() }} ₸</span></div>
                    <button class="partner__right-output"><span>-</span> вывести</button>
                </div>
                <div class="partner__right-item">
                    <a href="{{ route('partner.edit') }}" class="partner__right-edit"><img src="/images/cabinet/edit-icon.svg" alt="icon"> редактировать</a>
                </div>
                @if(!empty($user_profile->agreement))
                <div class="partner__right-item">
                    <input type="file" id="upload-contract" hidden="hidden">
                    <a href="{{$user_profile->getAgreementUrl()}}" target="_blank" style="line-height: 14px;" class="partner__right-upload-contract">
                        <img src="/images/cabinet/add-file.svg" alt="icon"> Скачать договор
                    </a>
                </div>
                @endif
                {{--<div class="partner__right-item">
                    <button class="partner__right-statistic"><img src="/images/cabinet/statistic.svg" alt="icon"> статистика</button>
                </div>--}}
                <div class="partner__right-item">
                    <a href="{{ route('logout') }}" class="partner__right-statistic"> Выйти из кабинета</a>
                </div>
                <div class="partner__right-item">
                    <a href="{{ route('logout') }}" class="partner__right-statistic"> Выйти из кабинета</a>
                </div>
            </div>
        </div>
        <div class="partner__main">
            <div class="partner__wrapper">
                <div class="partner__contacts">
                    <div class="partner__contacts-title">Контакты</div>
                    <div class="partner__contacts-item">
                        <div class="partner__contacts-icon">
                            <img src="/images/cabinet/phone-icon.svg" alt="icon">
                        </div>
                        <a href="#" class="partner__contacts-text">{{ $user_profile->phone }}</a>
                    </div>
                    <div class="partner__contacts-item">
                        <div class="partner__contacts-icon">
                            <img src="/images/cabinet/location.svg" alt="icon">
                        </div>
                        <p class="partner__contacts-text">{{ $user_profile->address }}</p>
                    </div>
                    <div class="partner__contacts-item">
                        <div class="partner__contacts-icon">
                            <img src="/images/cabinet/mail.svg" alt="icon">
                        </div>
                        <a href="#" class="partner__contacts-text">{{ $user_profile->email }}</a>
                    </div>
                    <div class="partner__contacts-item">
                        <div class="partner__contacts-icon">
                            <img src="/images/cabinet/website-icon.svg" alt="icon">
                        </div>
                        <a href="#" class="partner__contacts-text">{{ $user_profile->site }}</a>
                    </div>
                    <div class="partner__contacts-item">
                        <div class="partner__contacts-icon">
                            <img src="/images/cabinet/clock-graphic.svg" alt="icon">
                        </div>
                        <div class="partner__contacts-item-graphic">
                            <p class="partner__contacts-text">
                                {!! $user_profile->work_time !!}
                            </p>
                            {{--<p class="partner__contacts-text">ПН - с 10:00 до 20:00</p>
                            <p class="partner__contacts-text">ВТ - с 10:00 до 20:00</p>
                            <p class="partner__contacts-text">СР - с 10:00 до 20:00</p>
                            <p class="partner__contacts-text">ЧТ - с 10:00 до 20:00</p>
                            <p class="partner__contacts-text">ПТ - с 10:00 до 20:00</p>
                            <p class="partner__contacts-text">СБ - выходной</p>
                            <p class="partner__contacts-text">ВС - выходной</p>--}}
                        </div>
                    </div>
                    <div class="partner__contacts-item">
                        <div class="partner__contacts-icon">
                            <img src="/images/cabinet/rating-star.svg" alt="icon">
                        </div>
                        <p class="partner__contacts-text">4.6 / 5.0</p>
                    </div>
                </div>
                <div class="partner__requisites">
                    <div class="partner__requisites-title">Реквизиты</div>
                    <div class="partner__requisites-card-title">Банковская карта</div>
                    <div class="partner__requisites-card">
                        <div class="partner__requisites-card-number"><span>****</span><span>****</span><span>****</span><span class="partner__requisites-card-lastnumbers">{{ $user_profile->getLastNumberOfCard() }}</span></div>
                        <div class="partner__requisites-card-logo"><img src="/images/cabinet/card-logo.svg" alt="card-logo"></div>
                    </div>
                    <input type="file" id="upload-card" hidden="hidden">
                    {{--<button class="partner__requisites-card-upload">+ прикрепить</button>--}}
                    <div class="partner__requisites-info">
                        Прикрепи свою банковскую карту для быстрой и удобной оплаты {{--<span class="dots">...</span>
                        <span class="more-text">
                                    Прикрепи свою банковскую карту для быстрой и удобной оплаты
                                </span>
                        <a href="#" class="open-text">открыть</a>
                        <a href="#" class="close-text">закрыть</a>--}}
                    </div>

                    <div class="partner__requisites-bank-name">
                        <img src="/images/cabinet/bank-name.svg" alt="">
                        <p>Название банка</p>
                    </div>
                    <input value="{{ $user_profile->bank_name }}" disabled class="partner__requisites-bank-name-input" type="text">

                    <div class="partner__requisites-bank-accaunt">
                        <img src="/images/cabinet/bank-account.svg" alt="">
                        <p>Банковский счёт</p>
                    </div>
                    <input value="{{ $user_profile->bank_account }}" disabled class="partner__requisites-bank-accaunt-input" type="text">

                </div>
            </div>
            <div class="partner__descr">
                <div class="partner__descr-title">Описание</div>
                @if(empty($user_profile))
                    <div class="partner__descr-subtitle">К сожалению, тут еще ничего не заполнено :(</div>
                @else
                    <div class="partner__descr-subtitle">
                        {{ $user_profile->description }}
                    </div>
                @endif
            </div>

            @include('_partials._address')

            @include('_partials._images')

        </div>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".partdescr__header-slider", {
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: ".swiper-pagination",
                type: "fraction",
            },
        });
        var swiper2 = new Swiper(".partdescr__gift-slider", {
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: ".swiper-pagination",
            },
        });
        var swiper3 = new Swiper(".partdescr__address-slider", {
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: ".swiper-pagination",
            },
        });
    </script>
    <script src="/js/main.min.js"></script>
@stop
