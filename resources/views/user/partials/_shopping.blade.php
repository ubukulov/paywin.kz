@php
    $allAmount = 0;
    $count = count($payments);
    foreach($payments as $payment) {
        $allAmount += $payment->amount;
    }
@endphp

<div class="hpurchase__stat">
    <div class="hpurchase__stat-item vsego">
        <span>{{ $allAmount }} тг</span> всего
    </div>
    <div class="hpurchase__stat-item kolichestvo">
        <span>{{ $count }}</span> кол-во
    </div>
    <div class="hpurchase__stat-item sr-summa">
        <span>{{ round($allAmount / $count) }} тг</span> ср. сумма
    </div>
</div>
<div class="hpurchase__wrapper">
    <div class="hpurchase__inner">
        @foreach($payments as $payment)
            @php
                $partner_profile = $payment->partner->profile;
            @endphp
        <div class="hpurchase__item">
            <div class="hpurchase__item-logo">
                <img @if(empty($partner_profile->logo)) src="/images/history/logo.png" @else src="{{ $partner_profile->logo }}" @endif alt="logo">
            </div>
            <div class="hpurchase__item-name">
                {{ $partner_profile->company }}
            </div>
            <div class="hpurchase__item-status">покупка</div>
            <div class="hpurchase__item-sum">{{ $payment->amount }}тг</div>
            <div class="hpurchase__item-status" style="margin-left: 20px;">
                {{ $payment->created_at->format('d.m.Y H:i') }}
            </div>
        </div>
        @endforeach

    </div>

    {{--<div class="hpurchase__inner">
        <div class="hpurchase__date">24 февраля</div>
        <div class="hpurchase__item">
            <div class="hpurchase__item-logo">
                <img src="/images/history/logo.png" alt="logo">
            </div>
            <div class="hpurchase__item-name">Dodo Pizza</div>
            <div class="hpurchase__item-status">пополнил</div>
            <div class="hpurchase__item-sum">12400тг</div>
        </div>
        <div class="hpurchase__item">
            <div class="hpurchase__item-logo">
                <img src="/images/history/logo.png" alt="logo">
            </div>
            <div class="hpurchase__item-name">Samuraisushi</div>
            <div class="hpurchase__item-status">вывел</div>
            <div class="hpurchase__item-sum">5600тг</div>
        </div>
    </div>--}}
</div>
