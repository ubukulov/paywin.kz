@extends('partner.partner')
@section('content')
    @php
        use SimpleSoftwareIO\QrCode\Facades\QrCode;
    @endphp
    <div class="myqr">
        <div class="myqr__wrapper">
            <div class="myqr__title">QR код Вашей компании</div>
            <div class="myqr__descr">Для проведения акций и розыгрышей призов</div>
            <button class="myqr__share">
                <img src="/images/cabinet/share.svg" alt="icon">
                поделиться
            </button>
            <div class="myqr__qrcode">
                {{--<img src="/images/cabinet/Bitmap.svg" alt="qrcode">--}}
                {!! QrCode::size(201)->generate(route('paymentPage', ['slug' => $user_profile->category->slug, 'id' => $user_profile->user_id])); !!}
{{--                <img src="{!!QrCode::format('png')->generate(route('paymentPage', ['slug' => $user_profile->category->slug, 'id' => $user_profile->user_id]), 'QrCode.png', 'image/png')!!}">--}}
            </div>
            <div class="myqr__block">
                <div class="myqr__right">
                    <button class="myqr__download-qrcode">
                        <img src="/images/cabinet/download.svg" alt="icon">
                        скачать QR код
                    </button>
                    <div class="myqr__right-info">Используйте QR для создания своего маркетинг контента</div>
                </div>
                <div class="myqr__left">
                    <button class="myqr__download-booklet">
                        <img src="/images/cabinet/booklet-icon.svg" alt="icon">
                        скачать буклет
                    </button>
                    <div class="myqr__left-info">Используйте наш готовый буклет для запуска своей акции</div>
                    <div class="myqr__left-links">
                        <a href="#" class="myqr__left-link-stol">на стол</a>
                        <a href="#" class="myqr__left-link-instagram">instragram</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
