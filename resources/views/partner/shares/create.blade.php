@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="mypromo">
        <div class="mypromo__header">
            <div class="mypromo__header-title">
                СОЗДАВАЙТЕ <br> КРУТЫЕ АКЦИИ
            </div>
            <button class="mypromo__header-btn">+ новая акция</button>
        </div>

        <br>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active promo__nav-link" id="prize-tab" data-toggle="tab" href="#prize" role="tab" aria-controls="prize" aria-selected="true">приз</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link promo__nav-link" id="discount-tab" data-toggle="tab" href="#discount" role="tab" aria-controls="discount" aria-selected="false">скидка</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link promo__nav-link" id="cashback-tab" data-toggle="tab" href="#cashback" role="tab" aria-controls="cashback" aria-selected="false">cashback</a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link promo__nav-link" id="promo-tab" data-toggle="tab" href="#promo" role="tab" aria-controls="promo" aria-selected="false">промокод</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="prize" role="tabpanel" aria-labelledby="prize-tab">
                <form action="{{ route('partner.my-shares.store') }}" method="post" class="promo__prise-form">
                    @csrf
                    <input type="hidden" name="type" value="share">
                    <div class="promo__prise-input-block">
                        <label for="gift-name">Название подарка</label>
                        <input name="title" required placeholder="Введите.."/>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-quantity">Количество</label>
                        <input type="number" min="1" name="cnt" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">При заказе</label>
                        от <input type="number" class="time-s" min="1" name="from_order" required>
                        до <input type="number" class="time-po" min="1" name="to_order" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-coeficent-winning">Коэф. выигрыша</label>
                        <input type="number" min="1" name="c_winning" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">Время действия</label>
                        с <input type="text" name="from_date" class="time-s" value="{{ date('d.m.Y') }}">
                        по <input type="text" name="to_date" class="time-po" value="{{ date('d.m.Y') }}">
                    </div>

                    <div class="promo__btn-block">
                        <div class="promo__back-btn">
                            <a href="{{ route('partner.my-shares.index') }}"><img src="/images/cabinet/left-arrow.svg" align="left" alt="icon"> &nbsp; &nbsp; вернуться</a>
                        </div>
                        <button type="submit" class="promo__form-btn">добавить</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="discount" role="tabpanel" aria-labelledby="discount-tab">
                <form action="{{ route('partner.my-shares.store') }}" method="post" class="promo__prise-form">
                    @csrf
                    <input type="hidden" name="type" value="discount">
                    <div class="promo__prise-input-block">
                        <label for="gift-name">Название подарка</label>
                        <input name="title" required placeholder="Введите.."/>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-quantity">Количество</label>
                        <input type="number" min="1" name="cnt" required>
                    </div>

                    <div class="promo__discount-input-block">
                        <label for="discount-amount">Размер скидки</label>
                        <input type="number" name="size" min="1" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">При заказе</label>
                        от <input type="number" class="time-s" min="1" name="from_order" required>
                        до <input type="number" class="time-po" min="1" name="to_order" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-coeficent-winning">Максимальная сумма</label>
                        <input type="number" min="1" name="max_sum" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-coeficent-winning">Коэф. выигрыша</label>
                        <input type="number" min="1" name="c_winning" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">Время действия</label>
                        с <input type="text" name="from_date" class="time-s" value="{{ date('d.m.Y') }}">
                        по <input type="text" name="to_date" class="time-po" value="{{ date('d.m.Y') }}">
                    </div>

                    <div class="promo__btn-block">
                        <div class="promo__back-btn">
                            <a href="{{ route('partner.my-shares.index') }}"><img src="/images/cabinet/left-arrow.svg" align="left" alt="icon"> &nbsp; &nbsp; вернуться</a>
                        </div>
                        <button type="submit" class="promo__form-btn">добавить</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="cashback" role="tabpanel" aria-labelledby="cashback-tab">
                <form action="{{ route('partner.my-shares.store') }}" method="post" class="promo__prise-form">
                    @csrf
                    <input type="hidden" name="type" value="cashback">
                    <div class="promo__prise-input-block">
                        <label for="gift-name">Название подарка</label>
                        <input name="title" required placeholder="Введите.."/>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-quantity">Количество</label>
                        <input type="number" min="1" name="cnt" required>
                    </div>

                    <div class="promo__discount-input-block">
                        <label for="discount-amount">Размер cashback</label>
                        <input type="number" name="size" min="1" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">При заказе</label>
                        от <input type="number" class="time-s" min="1" name="from_order" required>
                        до <input type="number" class="time-po" min="1" name="to_order" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-coeficent-winning">Коэф. выигрыша</label>
                        <input type="number" min="1" name="c_winning" required>
                    </div>

                    <div class="promo__prise-input-block">
                        <label for="gift-time">Время действия</label>
                        с <input type="text" name="from_date" class="time-s" value="{{ date('d.m.Y') }}">
                        по <input type="text" name="to_date" class="time-po" value="{{ date('d.m.Y') }}">
                    </div>

                    <div class="promo__btn-block">
                        <div class="promo__back-btn">
                            <a href="{{ route('partner.my-shares.index') }}"><img src="/images/cabinet/left-arrow.svg" align="left" alt="icon"> &nbsp; &nbsp; вернуться</a>
                        </div>
                        <button type="submit" class="promo__form-btn">добавить</button>
                    </div>
                </form>
            </div>

            <div class="tab-pane fade" id="promo" role="tabpanel" aria-labelledby="promo-tab">
                @include('partner.shares.promo')
            </div>

        </div>

        {{--<div class="promo__nav">
            <ul>
                <li><a href="#" class="promo__nav-link promo__nav-link-active">приз</a></li>
                <li><a href="#" class="promo__nav-link">скидка</a></li>
                <li><a href="#" class="promo__nav-link">cashback</a></li>
                <li><a href="#" class="promo__nav-link">промокод</a></li>
            </ul>
        </div>--}}



    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

@endpush
