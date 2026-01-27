@extends('user.user')
@section('content')
    <style>
        body {
            font-family: Inter, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .promo-wrapper {
            width: 100%;
            max-width: 420px;
            text-align: center;
            color: #000;
        }

        .promo-slider {
            position: relative;
        }

        .promo-card {
            display: none;
            padding: 25px;
            border-radius: 18px;
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,.15);
            box-shadow: 0 10px 30px rgba(0,0,0,.25);
            animation: fade .5s ease;
        }

        .promo-card.active {
            display: block;
        }

        .promo-code {
            font-size: 22px;
            font-weight: 700;
            background: rgba(0,0,0,.25);
            padding: 10px;
            margin: 15px 0;
            border-radius: 10px;
            letter-spacing: 2px;
        }

        .promo-card button {
            margin-top: 15px;
            padding: 10px 20px;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            background: #00ffd5;
            font-weight: bold;
        }

        .promo-card button:hover {
            transform: scale(1.05);
        }

        .promo-nav {
            margin-top: 15px;
        }

        .promo-nav button {
            background: none;
            border: 2px solid #000;
            color: #000;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
        }

        @keyframes fade {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <div class="earn">
        <div class="earn__wrapper">

            <div class="promo-wrapper">
                <h2 style="margin-bottom: 20px;">🎁 Доступные промокоды</h2>

                <div class="promo-slider">
                    @foreach($promos as $promo)
                    <div class="promo-card @if($loop->iteration == 1) active @endif">
                        <h3>Для новых клиентов</h3>
                        <div class="promo-code">{{ $promo->title }}</div>
                        @if($promo->promo == 'discount')
                            <p>Скидка {{ $promo->size }}% на покупку</p>
                        @else
                            <p>Получите бонус в размере {{ $promo->size }}₸</p>
                        @endif
                        <span style="margin-right: 20px;">⏳ до {{ date('d.m.Y', strtotime($promo->to_date)) }}</span>
                        <button onclick="copyPromo('{{ $promo->getMyPromoLink() }}')">Получить ссылку</button>
                    </div>
                    @endforeach
                </div>

                <div class="promo-nav">
                    <button onclick="prev()">‹</button>
                    <button onclick="next()">›</button>
                </div>
            </div>

            @if(count($myPromos) > 0)
            <!-- МОИ ПРОМОКОДЫ -->
            <div class="promo-wrapper">
                <h2 style="margin-bottom: 20px;">🚀 Мои промокоды</h2>

                <div class="promo-slider">
                    @foreach($myPromos as $myPromo)
                    <div class="card">
                        <div class="card-left">
                            <div class="promo-code">{{ $myPromo->promo_code }}</div>
                            <div class="promo-desc">
                                Ваша персональная ссылка:
                            </div>

                            <div class="link-box">
                                {{ route('referral.link', ['code' => $myPromo->promo_code]) }}
                            </div>

                            <div class="stats">
                                Активировали: <b>{{ $myPromo->activatedCount() }}</b> &nbsp; • &nbsp;
                                Доход: <b>{{ $myPromo->getEarn() }}₸</b>
                            </div>
                        </div>

                        <div class="actions">
                            <button class="btn secondary">Поделиться</button>
                            <button class="btn">Скачать баннер</button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="promo-nav">
                    <button onclick="prev()">‹</button>
                    <button onclick="next()">›</button>
                </div>
            </div>
            @endif
            {{--<div class="earn__item">
                <div class="earn__promocode">newyear2018</div>
                <div class="earn__promocode-info">
                    <p>Активировали: <span>10000</span></p>
                    <p>Поделились: <span>7500</span></p>
                    <p>Доход: <span>2500</span></p>
                </div>
                <a href="#" class="earn__share">
                    <img src="{{ asset('images/profile/share.svg') }}" alt="">
                    поделиться
                </a>
                <a href="#" class="earn__download-banner">
                    <img src="{{ asset('images/profile/download-banner.svg') }}" alt="">
                    скачать баннер
                </a>
                <div class="earn__text">
                    Делитесь своим промо кодом с друзьями, они получат при регистрации бонусные 200тг на счет. Вы будете получать 1% с каждой покупки своего друга
                </div>
            </div>
            <div class="earn__item">
                <div class="earn__promocode">new8</div>
                <div class="earn__promocode-info">
                    <p>Активировали: <span>10000</span></p>
                    <p>Поделились: <span>7500</span></p>
                    <p>Доход: <span>2500</span></p>
                </div>
                <a href="#" class="earn__share">
                    <img src="{{ asset('images/profile/share.svg') }}" alt="">
                    поделиться
                </a>
                <a href="#" class="earn__download-banner">
                    <img src="{{ asset('images/profile/download-banner.svg') }}" alt="">
                    скачать баннер
                </a>
                <div class="earn__text">
                    Делитесь промо кодом с партнерами или регистрируйте их по своему промо коду и зарабатывайте до 30% с прибыли приложения по партнеру
                </div>
            </div>--}}
        </div>
    </div>
@stop

@push('user_scripts')
    <script>
        let index = 0;
        const cards = document.querySelectorAll('.promo-card');

        function showCard(i) {
            cards.forEach(c => c.classList.remove('active'));
            cards[i].classList.add('active');
        }

        function next() {
            index = (index + 1) % cards.length;
            showCard(index);
        }

        function prev() {
            index = (index - 1 + cards.length) % cards.length;
            showCard(index);
        }

        function copyPromo(code) {
            navigator.clipboard.writeText(code);
            alert('Промокод скопирован: ' + code);
        }
    </script>
@endpush
