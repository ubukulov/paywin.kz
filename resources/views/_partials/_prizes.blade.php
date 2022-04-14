@php
    $partner = \App\Models\User::find($share->user->id);
    $partner_profile = $partner->profile;
@endphp
<div class="prize prize--1 prizes__item">
    <div class="company prize__company">
        <img @if(empty($partner_profile->logo)) src="/img/logotypes/papa-johson.png" @else src="{{ $partner_profile->logo }}" @endif alt="{{ $partner_profile->company }}" class="company__logo">
        <h2 class="company__title">{{ $partner_profile->company }}</h2>
    </div>
    <div class="prize__info">
        <p class="prize__text">Призы: <b>{{ $share->cnt }}</b><br>Заказ от: <b>{{ $share->from_order }}₸</b></p>
        <div class="prize__slider">
            <div class="slider__item">
                <p class="slide__text">{{ $share->title }}<br>при заказе от {{ $share->from_order }}₸</p>
            </div>
        </div>
    </div>
</div>
