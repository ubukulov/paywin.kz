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
                @php
                    $imgUrl = "/qrcodes/" . $user_profile->company . "_qr_code.svg";
                @endphp
                {!! QrCode::size(201)->generate(route('paymentPage', ['slug' => $user_profile->category->slug, 'id' => $user_profile->user_id])); !!}
                {!! QrCode::size(201)->generate(route('paymentPage', ['slug' => $user_profile->category->slug, 'id' => $user_profile->user_id]), public_path() . $imgUrl); !!}
            </div>
            <div class="myqr__block">
                <div class="myqr__right">
                    <a href="{{ asset($imgUrl) }}" target="_blank" class="myqr__download-qrcode">
                        <img src="/images/cabinet/download.svg" alt="icon">
                        скачать QR код
                    </a>
                    <div class="myqr__right-info">Используйте QR для создания своего маркетинг контента</div>
                </div>
                <div class="myqr__left">
                    <a href="{{ asset('files/booklet.png') }}" target="_blank" class="myqr__download-booklet">
                        <img src="/images/cabinet/booklet-icon.svg" alt="icon">
                        скачать буклет
                    </a>
                    <div class="myqr__left-info">Используйте наш готовый буклет для запуска своей акции</div>
                    {{--<div class="myqr__left-links">
                        <a href="#" class="myqr__left-link-stol">на стол</a>
                        <a href="#" class="myqr__left-link-instagram">instragram</a>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@stop
