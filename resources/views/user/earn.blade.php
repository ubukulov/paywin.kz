@extends('user.user')

@section('content')
    <div class="p-4 pb-20 space-y-8">

        {{-- БЛОК 1: ДОСТУПНЫЕ ПРЕДЛОЖЕНИЯ --}}
        <section>
            <h2 class="text-xl font-extrabold text-gray-900 mb-4 flex items-center gap-2">
                <span>🎁</span> Выбери и заработай
            </h2>

            <div class="swiper available-promos">
                <div class="swiper-wrapper">
                    @foreach($promos as $promo)
                        <div class="card">
                            <div class="card-left">
                                {{-- Берем название из связанной акции --}}
                                <div class="promo-code">{{ $promo->share->title ?? 'Без названия' }}</div>

                                <div class="promo-desc">
                                    Ваша персональная ссылка:
                                </div>

                                <div class="link-box">
                                    {{-- Генерируем ссылку, передавая ID агента и ID акции --}}
                                    {{ route('referral.link', ['agent' => auth()->id(), 'share' => $promo->share_id]) }}
                                </div>

                                <div class="stats">
                                    Активировали: <b>{{ $promo->activatedCount() }}</b> &nbsp; • &nbsp;
                                    Доход: <b>{{ number_format($promo->getEarn(), 0) }} ₸</b>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination !-bottom-1 mt-4"></div>
            </div>
        </section>

        {{-- БЛОК 2: МОИ РЕЗУЛЬТАТЫ --}}
        @if($myPromos->isNotEmpty())
            <section>
                <h2 class="text-xl font-extrabold text-gray-900 mb-4">🚀 Моя активность</h2>
                <div class="space-y-4">
                    @foreach($myPromos as $myPromo)
                        <div class="card">
                            <div class="card-left">
                                {{-- Берем название из связанной акции --}}
                                <div class="promo-code">{{ $myPromo->share->title ?? 'Без названия' }}</div>

                                <div class="promo-desc">
                                    Ваша персональная ссылка:
                                </div>

                                <div class="link-box">
                                    {{-- Генерируем ссылку, передавая ID агента и ID акции --}}
                                    {{ route('referral.link', ['agent' => auth()->id(), 'share' => $myPromo->share_id]) }}
                                </div>

                                <div class="stats">
                                    Активировали: <b>{{ $myPromo->activatedCount() }}</b> &nbsp; • &nbsp;
                                    Доход: <b>{{ number_format($myPromo->getEarn(), 0) }} ₸</b>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
@stop
