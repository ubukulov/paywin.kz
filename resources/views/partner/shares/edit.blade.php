@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="mypromo">
        <div class="mypromo__header">
            <div class="mypromo__header-title">
                Редактировать <br>
                @if($share->type == 'share')
                    приз
                @elseif($share->type == 'discount')
                    скидку
                @elseif($share->type == 'cashback')
                    кэшбека
                @else
                    промокод
                @endif
            </div>
        </div>

        @if($share->type == 'share')
            @include('partner.shares.types.edit.gift-edit')
        @endif

        @if($share->type == 'discount')
            @include('partner.shares.types.edit.discount-edit')
        @endif

        @if($share->type == 'cashback')
            @include('partner.shares.types.edit.cashback-edit')
        @endif

        @if($share->type == 'promocode')
            @include('partner.shares.types.edit.promo-edit')
        @endif
    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

@endpush
