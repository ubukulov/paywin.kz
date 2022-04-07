@extends('partner.partner')

@section('content')
    <div class="clients">
        <div class="clients__stat">
            <div class="clients__stat-item">
                <p class="clients__stat-text">Кол-во <br> клиентов <br> <span>350</span></p>
            </div>
            <div class="clients__stat-item">
                <p class="clients__stat-text">Кол-во <br> покупок <br> <span>12350</span></p>
            </div>
            <div class="clients__stat-item">
                <p class="clients__stat-text">MAX <br> покупока <br> <span>125500</span></p>
            </div>
            <div class="clients__stat-item">
                <p class="clients__stat-text">Получили <br> призов <br> <span>120</span></p>
            </div>
        </div>
        <div class="clients__filter">
            <ul>
                <li><a href="#" class="clients__filter-link clients__filter-link-active">сегодня</a></li>
                <li><a href="#" class="clients__filter-link">месяц</a></li>
                <li><a href="#" class="clients__filter-link">за всё время</a></li>
                <li><a href="#" class="clients__filter-link">указать период</a></li>
            </ul>
        </div>
        <div class="clients__nav">
            <ul>
                <li><a class="clients__nav-link clients__nav-link-active" href="#">пользователи</a></li>
                <li><a class="clients__nav-link" href="#">маркетинг</a></li>
            </ul>
        </div>
        <div class="clients__users">
            <div class="clients__users-search-box">
                <input type="text" class="clients__users-search" placeholder="Начните вводить имя..">
                <button class="clients__users-search-btn"><img src="/images/clients/search-icon.svg" alt="search"></button>
            </div>

            <div class="clients__users-item">
                <div class="clients__users-item-left">
                    <div class="clients__users-logo"><img src="/images/clients/logo.png" alt="logo"></div>
                    <a href="#" class="clients__users-requisites">реквизиты</a>
                    <a href="#" class="clients__users-send-present">отправить подарок</a>
                </div>
                <div class="clients__users-item-right">
                    <div class="clients__users-user">Пользователь</div>
                    <div class="clients__users-wallet-block">
                        <img src="/images/clients/wallet-icon.svg" alt="icon">
                        <p class="clients__users-wallet-sum">на счету <br> <span>250 ₸</span></p>
                        <p class="clients__users-wallet-cashback">+350 Cashback</p>
                    </div>
                    <p class="clients__users-win-prizes">Выиграл призов: <span>1259</span></p>
                    <div class="clients__users-purchases">
                        <div class="clients__users-purchases-title">Покупки:</div>
                        <div class="clients__users-purchases-table">
                            <div class="clients__users-purchases-table-head">
                                <div class="clients__users-purchases-table-head-block">кол-во</div>
                                <div class="clients__users-purchases-table-head-block">сумма</div>
                            </div>
                            <div class="clients__users-purchases-table-body">
                                <div class="clients__users-purchases-table-body-block">1200</div>
                                <div class="clients__users-purchases-table-body-block">12500тг</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clients__users-item">
                <div class="clients__users-item-left">
                    <div class="clients__users-logo"><img src="/images/clients/logo.png" alt="logo"></div>
                    <a href="#" class="clients__users-requisites">реквизиты</a>
                    <a href="#" class="clients__users-send-present">отправить подарок</a>
                </div>
                <div class="clients__users-item-right">
                    <div class="clients__users-user">Пользователь</div>
                    <div class="clients__users-wallet-block">
                        <img src="/images/clients/wallet-icon.svg" alt="icon">
                        <p class="clients__users-wallet-sum">на счету <br> <span>250 ₸</span></p>
                        <p class="clients__users-wallet-cashback">+350 Cashback</p>
                    </div>
                    <p class="clients__users-win-prizes">Выиграл призов: <span>1259</span></p>
                    <div class="clients__users-purchases">
                        <div class="clients__users-purchases-title">Покупки:</div>
                        <div class="clients__users-purchases-table">
                            <div class="clients__users-purchases-table-head">
                                <div class="clients__users-purchases-table-head-block">кол-во</div>
                                <div class="clients__users-purchases-table-head-block">сумма</div>
                            </div>
                            <div class="clients__users-purchases-table-body">
                                <div class="clients__users-purchases-table-body-block">1200</div>
                                <div class="clients__users-purchases-table-body-block">12500тг</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('partner_scripts')
    <script src="/js/clients.js"></script>
@endpush
