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
                акции
            </div>
        </div>

        <br>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link @if($share->type == 'share') active @else disabled @endif promo__nav-link" id="prize-tab" data-toggle="tab" href="#prize" role="tab" aria-controls="prize" aria-selected="true">приз</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link @if($share->type == 'discount') active @else disabled @endif promo__nav-link" id="discount-tab" data-toggle="tab" href="#discount" role="tab" aria-controls="discount" aria-selected="false">скидка</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link @if($share->type == 'cashback') active @else disabled @endif promo__nav-link" id="cashback-tab" data-toggle="tab" href="#cashback" role="tab" aria-controls="cashback" aria-selected="false">cashback</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link @if($share->type == 'promocode') active @else disabled @endif promo__nav-link" id="promo-tab" data-toggle="tab" href="#promo" role="tab" aria-controls="promo" aria-selected="false">промокод</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade @if($share->type == 'share') show active @endif" id="prize" role="tabpanel" aria-labelledby="prize-tab">
                <form action="{{ route('partner.my-shares.update', ['my_share' => $share->id]) }}" method="post" class="promo__prise-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="share">
                    <div class="promo__prise-input-block">
                        <label for="gift-name">Название подарка</label>
                        <input name="title" value="{{ $share->title }}" required placeholder="Введите.."/>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-quantity">Количество</label>
                        <input type="number" value="{{ $share->cnt }}" min="1" name="cnt" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">При заказе</label>
                        от <input type="number" value="{{ $share->from_order }}" class="time-s" min="1" name="from_order" required>
                        до <input type="number" value="{{ $share->to_order }}" class="time-po" min="1" name="to_order" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-coeficent-winning">Коэф. выигрыша</label>
                        <input type="number" min="1" value="{{ $share->c_winning }}" name="c_winning" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">Время действия</label>
                        с <input type="text" name="from_date" class="time-s" value="{{ $share->from_date }}">
                        по <input type="text" name="to_date" class="time-po" value="{{ $share->to_date }}">
                    </div>

                    <div class="promo__btn-block">
                        <div class="promo__back-btn">
                            <a href="{{ route('partner.my-shares.index') }}"><img src="/images/cabinet/left-arrow.svg" align="left" alt="icon"> &nbsp; &nbsp; вернуться</a>
                        </div>
                        <button type="submit" class="promo__form-btn">обновить</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade @if($share->type == 'discount') show active @endif" id="discount" role="tabpanel" aria-labelledby="discount-tab">
                <form action="{{ route('partner.my-shares.update', ['my_share' => $share->id]) }}" method="post" class="promo__prise-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="discount">
                    <div class="promo__prise-input-block">
                        <label for="gift-name">Название подарка</label>
                        <input name="title" value="{{ $share->title }}" required placeholder="Введите.."/>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-quantity">Количество</label>
                        <input type="number" value="{{ $share->cnt }}" min="1" name="cnt" required>
                    </div>

                    <div class="promo__discount-input-block">
                        <label for="discount-amount">Размер скидки</label>
                        <input type="number" value="{{ $share->size }}" name="size" min="1" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">При заказе</label>
                        от <input type="number" value="{{ $share->from_order }}" class="time-s" min="1" name="from_order" required>
                        до <input type="number" class="time-po" value="{{ $share->to_order }}" min="1" name="to_order" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-coeficent-winning">Коэф. выигрыша</label>
                        <input type="number" min="1" value="{{ $share->c_winning }}" name="c_winning" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">Время действия</label>
                        с <input type="text" name="from_date" class="time-s" value="{{ $share->from_date }}">
                        по <input type="text" name="to_date" class="time-po" value="{{ $share->to_date }}">
                    </div>

                    <div class="promo__btn-block">
                        <div class="promo__back-btn">
                            <a href="{{ route('partner.my-shares.index') }}"><img src="/images/cabinet/left-arrow.svg" align="left" alt="icon"> &nbsp; &nbsp; вернуться</a>
                        </div>
                        <button type="submit" class="promo__form-btn">обновить</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade @if($share->type == 'cashback') show active @endif" id="cashback" role="tabpanel" aria-labelledby="cashback-tab">
                <form action="{{ route('partner.my-shares.update', ['my_share' => $share->id]) }}" method="post" class="promo__prise-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="cashback">
                    <div class="promo__prise-input-block">
                        <label for="gift-name">Название подарка</label>
                        <input name="title" value="{{ $share->title }}" required placeholder="Введите.."/>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-quantity">Количество</label>
                        <input type="number" value="{{ $share->cnt }}" min="1" name="cnt" required>
                    </div>

                    <div class="promo__discount-input-block">
                        <label for="discount-amount">Размер cashback</label>
                        <input type="number" value="{{ $share->size }}" name="size" min="1" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">При заказе</label>
                        от <input type="number" value="{{ $share->from_order }}" class="time-s" min="1" name="from_order" required>
                        до <input type="number" value="{{ $share->to_order }}" class="time-po" min="1" name="to_order" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-coeficent-winning">Коэф. выигрыша</label>
                        <input type="number" min="1" value="{{ $share->c_winning }}" name="c_winning" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">Время действия</label>
                        с <input type="text" name="from_date" class="time-s" value="{{ $share->from_date }}">
                        по <input type="text" name="to_date" class="time-po" value="{{ $share->to_date }}">
                    </div>

                    <div class="promo__btn-block">
                        <div class="promo__back-btn">
                            <a href="{{ route('partner.my-shares.index') }}"><img src="/images/cabinet/left-arrow.svg" align="left" alt="icon"> &nbsp; &nbsp; вернуться</a>
                        </div>
                        <button type="submit" class="promo__form-btn">обновить</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade @if($share->type == 'promocode') show active @endif" id="promo" role="tabpanel" aria-labelledby="promo-tab">
                <form action="{{ route('partner.my-shares.update', ['my_share' => $share->id]) }}" method="post" class="promo__prise-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="promocode">
                    <div class="promo__prise-input-block">
                        <label for="gift-name">Название подарка</label>
                        <input name="title" value="{{ $share->title }}" required placeholder="Введите.."/>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-quantity">Количество</label>
                        <input type="number" min="1" value="{{ $share->cnt }}" name="cnt" required>
                    </div>

                    <div class="promo__promocode-input-block">
                        {{--<div class="promo__promocode-input-text">
                            <span>Скидка</span> <input type="checkbox" value="1" name="discount_or_money" width="21px" height="11px"> деньги
                        </div>--}}
                        <div style="display: inline-flex; width: 50px;">
                            <span>Скидка</span>
                        </div>
                        <div style="display: inline-flex; width: 100px;" class="custom-control custom-switch">
                            <input type="checkbox" name="discount_or_money" class="custom-control-input" id="customSwitch1">
                            <label class="custom-control-label" for="customSwitch1">Деньги</label>
                        </div>
                        <input type="number" value="{{ $share->size }}" min="1" name="size" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">При заказе</label>
                        от <input type="number" value="{{ $share->from_order }}" class="time-s" min="1" name="from_order" required>
                        до <input type="number" value="{{ $share->to_order }}" class="time-po" min="1" name="to_order" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-coeficent-winning">Коэф. выигрыша</label>
                        <input type="number" value="{{ $share->c_winning }}" min="1" name="c_winning" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">Время действия</label>
                        с <input type="text" name="from_date" class="time-s" value="{{ $share->from_date }}">
                        по <input type="text" name="to_date" class="time-po" value="{{ $share->to_date }}">
                    </div>
                    <div class="promo__btn-block">
                        <div class="promo__back-btn">
                            <a href="{{ route('partner.my-shares.index') }}"><img src="/images/cabinet/left-arrow.svg" align="left" alt="icon"> &nbsp; &nbsp; вернуться</a>
                        </div>
                        <button type="submit" class="promo__form-btn">обновить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

@endpush
