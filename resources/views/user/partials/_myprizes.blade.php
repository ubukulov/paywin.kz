@php
    $partner = \App\Models\User::find($prize->share->user_id);
    $partner_profile = $partner->profile;
@endphp

<div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow duration-300">
    <div class="flex items-center justify-between gap-4">

        <div class="flex items-center gap-4 min-w-0">
            <div class="w-12 h-12 flex-shrink-0 bg-gray-50 rounded-2xl p-2 border border-gray-50">
                <img @if(empty($partner_profile->logo)) src="/images/my-prizes/logo.png" @else src="{{ $partner_profile->logo }}" @endif
                class="w-full h-full object-contain" alt="logo">
            </div>
            <div class="min-w-0">
                <h4 class="font-bold text-gray-900 leading-tight truncate">{{ $partner_profile->company }}</h4>
                <div class="flex items-center gap-2 mt-1">
                    <img src="/images/my-prizes/calendar.svg" class="w-3 h-3 opacity-30" alt="">
                    <span class="text-[11px] text-gray-400">{{ date('d.m.Y', strtotime($prize->updated_at)) }}</span>
                </div>
            </div>
        </div>

        <div class="text-right flex-shrink-0">
            <div class="text-lg font-bold text-gray-900">{{ $prize->payment->amount }} ₸</div>

            @if($prize->status == 'got')
                <div class="inline-flex items-center gap-1 text-[10px] font-bold text-green-500 uppercase tracking-tighter">
                    <span class="w-1 h-1 bg-green-500 rounded-full"></span>
                    получено
                </div>
            @else
                <div class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-500 uppercase tracking-tighter">
                    <span class="w-1 h-1 bg-amber-500 rounded-full"></span>
                    ожидание
                </div>
            @endif
        </div>
    </div>

    <div class="my-4 border-t border-gray-50"></div>

    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center">
                <img src="/images/my-prizes/check.svg" class="w-4 h-4" alt="">
            </div>
            <p class="text-xs text-gray-600 font-medium">
                {{ $prize->share->title }}
            </p>
        </div>
        <div class="text-[10px] bg-gray-100 text-gray-500 px-2 py-1 rounded-lg whitespace-nowrap">
            от {{ $prize->share->from_order }} ₸
        </div>
    </div>
</div>
