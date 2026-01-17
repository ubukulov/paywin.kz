@extends('user.user')
@section('content')
    <div class="profile__wrapper">
        <div class="profile__left">
            <div class="profile__avatar">
                <img src="/images/profile/avatar.png" alt="">
            </div>
            <div class="profile__balance">
                <img src="/images/profile/wallet.svg" alt="">
                <p class="profile__balance-sum">на счету <br> <span>{{ $user->getBalanceForUser() }} ₸</span></p>
{{--                <a href="#" class="profile__balance-replenish">+</a>--}}
                <button style="border: none;" type="button" class="btn btn-primary profile__balance-replenish" data-toggle="modal" data-target="#exampleModal">+</button>
            </div>
            {{--<p class="profile__bonus">+<span>350</span> бонусов</p>--}}
            <div class="profile__bank-card-block">
                <div class="profile__bank-card-title">Банковская карта</div>
                {{--<div class="profile__bank-card">
                    <div class="profile__bank-card-number"><span>****</span><span>****</span><span>****</span><span class="profile__bank-card-lastnumbers">9981</span></div>
                    <div class="profile__bank-card-logo"><img src="/images/profile/card-logo.svg" alt="card-logo"></div>
                </div>--}}
                @if(count($user->getMyCards()) > 0)
                <div class="action__flex">
                    <select name="card_id" style="font-size: 14px;border-color: #ccc;border-radius: 5px;color: green;">
                        @foreach($user->getMyCards() as $card)
                            <option value="{{ $card['id'] }}">{{ $card['number'] }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <a href="{{ route('user.addMyCard') }}" class="profile__bank-card-upload">+ прикрепить</a>
            </div>
        </div>
        <div class="profile__right">
            <a href="{{ route('user.settings') }}" class="profile__settings">
                <img src="/images/profile/settings.svg" alt="">
            </a>
            <div class="profile__username">
                <img src="/images/profile/user-icon.svg" alt="">
                <span>{{ $user_profile->full_name }}</span>
            </div>

            <div class="profile__sex">
                @if($user_profile->sex == 1)
                    <img src="/images/profile/female.svg" alt="">
                    <span>Мужской</span>
                @elseif($user_profile->sex == 2)
                    <img src="/images/profile/male-icon.svg" alt="">
                    <span>Женский</span>
                @endif
            </div>

            <div class="profile__birthdate">
                <img src="/images/profile/birthday-cake.svg" alt="">
                <span>{{ $user_profile->birth_date }}</span>
            </div>
            <div class="profile__telephone-number">
                <img src="/images/profile/mobile-phone.svg" alt="">
                <span>{{ $user_profile->phone }}</span>
            </div>
            <div class="profile__email">
                <img src="/images/profile/mail.svg" alt="">
                <span>{{ $user_profile->email }}</span>
            </div>

            <div>
                <i style="color: #BABABA;" class="fa fa-link"></i>
                <span style="font-size: 14px; color: #BABABA;">{{ route('referral.link', ['code' => $user->id]) }}</span>
            </div>
            {{--<p class="profile__active-promocode">Активировать промокод</p>
            <div class="profile__promocode-input">
                <input type="text" placeholder="Введите промокод">
                <a href="#" class="profile__promocode-scanner">
                    <img src="/images/profile/scanner-promocode-icon.svg" alt="scanner">
                </a>
                <button class="profile__promocode-btn" type="submit">OK</button>
            </div>--}}

            @if($prize)
                @php
                    $share = $prize->share;
                    $partner = $share->user;
                    $partner_profile = $partner->profile;
                @endphp
                <div class="profile__surprize">
                    <div class="profile__surprize-logo">
                        <img src="/images/profile/logo.png" alt="logo">
                    </div>
                    <div>
                        <div class="profile__surprize-info">
                            Подарок от <br> <span class="profile__surprize-company">{{ $partner_profile->company }}</span> <br> <span class="profile__surprize-sum">{{ $share->title }}</span>
                        </div>
                        <a href="{{ route('user.getMyPrize', ['prize_id' => $prize->id]) }}" class="profile__surprize-btn">
                            <img src="/images/profile/check.svg" alt="">
                            получить
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <div class="profile__info">
        Прикрепи свою банковскую карту для быстрой и удобной оплаты Не беспокойся, твои данные под надежной защитой!
    </div>
    {{--<a href="#" class="profile__user-agreement">Пользовательское соглашение</a>--}}

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('user.balanceReplenishment') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Пополнение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="amount" placeholder="Введите сумму" required class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Продолжить</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@stop
