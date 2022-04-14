<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Описание партнера</title>
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/swiper-boundle.min.css">
    <link rel="stylesheet" href="/css/style.min.css">
</head>
<body>

<div class="container">
    <div class="partdescr">
        <div class="partdescr__header">
            <div class="partdescr__header-slider">
                <div class="swiper-wrapper">
                    @foreach($partner->images as $image)
                        <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 5" style="width: 480px;">
                            <img src="{{ $image->image }}" alt="photo">
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <a href="#" class="partdescr__back-btn">
                <img src="/images/partner-description/left-arrow.svg" alt="back">
            </a>
            <a href="#" class="partdescr__share">
                <img src="/images/partner-description/share.svg" alt="share">
            </a>
            <a href="#" class="partdescr__camera">
                <img src="/images/partner-description/camera-icon.svg" alt="camera">
            </a>
            <div class="partdescr__rating-block">
                <div class="partdescr__rating-num">4.2</div>
                <div class="partdescr__rating-stars">
                    <i class="fas fa-star full"></i>
                    <i class="fas fa-star full"></i>
                    <i class="fas fa-star full"></i>
                    <i class="fas fa-star full"></i>
                    <i class="fas fa-star empty"></i>
                </div>
                <div class="partdescr__rating-score">
                    <span>350</span>
                    оценок
                </div>
            </div>
        </div>
        <div class="partdescr__main">
            <div class="partdescr__main-fb">
                <div class="partdescr__profile">
                    <div class="partdescr__profile-logo">
                        <img @if(empty($profile->logo)) src="/images/partner-description/partner-logo.png" @else src="{{ $profile->logo }}" @endif alt="logo">
                    </div>
                    <div class="partdescr__profile-block">
                        <div class="partdescr__profile-name">{{ $profile->company }}</div>
                        <div class="partdescr__profile-descr">{{ $profile->category->title }}</div>
                    </div>
                </div>
                <div class="partdescr__gift-slider">
                    <div class="swiper-wrapper">

                        @foreach($partner->shares as $share)
                        <div class="swiper-slide">
                            <div class="partdescr__gift-slider-info">при заказе от {{ $share->from_order }} тг</div>
                            <div class="partdescr__gift-slider-card">
                                <img src="/images/partner-description/slider-elem.svg" alt="">
                                <p>{{ \Illuminate\Support\Str::limit($share->title, 14) }} <br> до {{ $share->to_order }}₸</p>
                            </div>
                        </div>
                        @endforeach

                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>

            @include('_partials._address')

            <div class="partdescr__description">
                <div class="partdescr__description-title">Описание</div>
                <p>
                    {{ $profile->description }}
                </p>
            </div>
            <div class="partdescr__time">
                <div class="partdescr__time-title">Время работы</div>
                <p>
                    {!! $profile->work_time !!}
                </p>
            </div>
            <div class="partdescr__social">
                <div class="partdescr__social-title">Социальные сети</div>
                <div class="partdescr__social-block">
                    @if(!empty($profile->vk))
                        <a href="{{ $profile->vk }}" target="_blank" class="partdescr__social-link">
                            <img src="/images/partner-description/vk-icon.svg" alt="vk">
                        </a>
                    @endif

                    @if(!empty($profile->telegram))
                        <a href="{{ $profile->telegram }}" target="_blank" class="partdescr__social-link">
                            <img src="/images/partner-description/telegram-icon.svg" alt="telegram">
                        </a>
                    @endif

                    @if(!empty($profile->instagram))
                        <a href="{{ $profile->instagram }}" target="_blank" class="partdescr__social-link">
                            <img src="/images/partner-description/insta-icon.svg" alt="instagram">
                        </a>
                    @endif
                </div>
            </div>
            <div class="partdescr__pay">
                <a href="{{ route('paymentPage', ['slug' => $slug, 'id' => $id]) }}" class="partdescr__pay-block">
                    <img src="/images/partner-description/pay-icon.svg" alt="icon">
                    оплатить
                </a>
            </div>
        </div>
    </div>
</div>


<script src="/js/swiper-boundle.min.js"></script>
<script src="/js/partdescr.js"></script>
</body>
</html>
