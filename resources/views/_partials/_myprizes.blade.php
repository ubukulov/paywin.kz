@php
    $partner = \App\Models\User::find($prize->share->user_id);
    $partner_profile = $partner->profile;
@endphp

<div class="myprize">
    <div class="myprize__wrapper" style="margin-top: 0px; padding-bottom: 0px; margin-bottom: 0px;">

        <div class="myprize__item">
            <div class="myprize__left">
                <div class="myprize__logo">
                    <img @if(empty($partner_profile->logo)) src="/images/my-prizes/logo.png" @else src="{{ $partner_profile->logo }}" @endif alt="">
                </div>
                <div class="myprize__name">{{ $partner_profile->company }}</div>
            </div>

            <div class="myprize__right">
                <div class="myprize__date">
                    <div class="myprize__date-icon">
                        <img src="/images/my-prizes/calendar.svg" alt="icon">
                    </div>
                    <div class="myprize__date-time">{{ date('d.m.Y', strtotime($prize->updated_at)) }} в {{ date('H:i', strtotime($prize->updated_at)) }}</div>
                </div>
                <div class="myprize__status">
                    <div class="myprize__status-icon">
                        <img src="/images/my-prizes/check.svg" alt="">
                    </div>
                    @if($prize->status == 'got')
                    <div class="myprize__status-result">получено</div>
                    @else
                    <div class="myprize__status-result">ожидание</div>
                    @endif
                </div>
                <div class="myprize__balance">
                    <div class="myprize__balance-icon">
                        <img src="/images/my-prizes/balance-icon.svg" alt="">
                    </div>
                    <div class="myprize__balance-sum">{{ $prize->payment->amount }}₸</div>
                </div>
                <div class="myprize__card">
                    <div class="myprize__card-bg">
                        <img src="/images/my-prizes/received.svg" alt="">
                    </div>
                    <div class="myprize__card-text">
                        {{ \Illuminate\Support\Str::limit($prize->share->title, 14) }} при заказе от {{ $prize->share->from_order }}₸
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
