@extends('user.user')
@push('user_styles')
    <style>
        .hbalance__replenish-btn, .hbalance__output-btn {
            height: 16px !important;
            text-align: center;
        }
    </style>
@endpush
@section('content')
    <div class="hbalance">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active promo__nav-link" id="balance-tab" data-toggle="tab" href="#balance" role="tab" aria-controls="balance" aria-selected="true">Баланс</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link promo__nav-link" id="shop-tab" data-toggle="tab" href="#shop" role="tab" aria-controls="shop" aria-selected="false">Покупки</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link promo__nav-link" id="income-tab" data-toggle="tab" href="#income" role="tab" aria-controls="income" aria-selected="false">Доходы</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="balance" role="tabpanel" aria-labelledby="balance-tab">
                @include('user.partials._balance')
            </div>

            <div class="tab-pane fade show" id="shop" role="tabpanel" aria-labelledby="shop-tab">
                @include('user.partials._shopping')
            </div>

            <div class="tab-pane fade show" id="income" role="tabpanel" aria-labelledby="income-tab">
                @include('user.partials._income')
            </div>
        </div>


        {{--<div class="hbalance__choose">
            <ul>
                <li><a href="#" class="hbalance__choose-link is-active">Баланс</a></li>
                <li><a href="#" class="hbalance__choose-link">Покупки</a></li>
                <li><a href="#" class="hbalance__choose-link">Доходы</a></li>
            </ul>
        </div>--}}

    </div>
@stop
