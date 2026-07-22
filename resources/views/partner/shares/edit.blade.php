@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="mypromo">

        <div class="relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-violet-600 to-fuchsia-600 p-8 rounded-[2.5rem] shadow-xl">
            {{-- Декоративный блюр - обязательно внутри overflow-hidden --}}
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <h1 class="text-3xl md:text-4xl font-black text-white leading-tight tracking-tight uppercase">
                    Редактировать <br>
                    @if($share->type == 'gift')
                    <span class="text-violet-200">приз</span>
                    @elseif($share->type == 'discount')
                    <span class="text-violet-200">скидку</span>
                    @elseif($share->type == 'cashback')
                    <span class="text-violet-200">кэшбека</span>
                    @else
                    <span class="text-violet-200">промокод</span>
                    @endif
                </h1>
            </div>
        </div>

        @if($share->type == 'gift')
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
