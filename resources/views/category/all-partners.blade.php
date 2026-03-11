@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 prizes-page">
        <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($partners as $partner)
                @php
                    $shares = $partner->shares;
                    $partnerProfile = $partner->partnerProfile;
                    $cashback = 0;
                @endphp

                @if($shares->isEmpty())
                    @continue
                @endif

                <div class="prize bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between transition-all hover:shadow-md">

                    {{-- Секция компании --}}
                    <div class="company flex items-center gap-4 mb-5">
                        <img
                            src="{{ empty($partnerProfile->logo) ? '/images/cabinet/papa-johns-pizza.svg' : $partnerProfile->logo }}"
                            alt="{{ $partnerProfile->company }}"
                            class="w-12 h-12 object-contain rounded-md"
                        >
                        <h2 class="text-xl font-bold text-gray-800">{{ $partnerProfile->company }}</h2>
                    </div>

                    {{-- Инфо о призах --}}
                    <div class="prize__info">
                        <p class="text-gray-600 text-sm leading-relaxed mb-4">
                            Призов: <b class="text-gray-900">{{ $shares->sum('cnt') }}</b><br>
                            Заказ от: <b class="text-gray-900">{{ number_format($shares->min('from_order'), 0, '.', ' ') }}₸</b>
                        </p>

                        <div class="prize__slider space-y-4">
                            @if($cashback && count($cashback) > 0)
                                <div class="bg-orange-50 p-3 rounded-lg border-l-4 border-[#FD9B11]">
                                    <p class="text-sm font-medium text-gray-700">
                                        Cashback <span class="text-[#FD9B11] font-bold">{{ $cashback->size }}%</span><br>
                                        <span class="text-xs text-gray-500 text-nowrap">при заказе от {{ number_format($cashback->from_order, 0, '.', ' ') }}₸</span>
                                    </p>
                                </div>
                            @endif

                            <div class="flex justify-center mt-auto">
                                <a href="{{ route('showPartner', ['slug' => $partnerProfile->category->slug, 'id' => $partnerProfile->partner_id]) }}"
                                   class="inline-flex items-center justify-center gap-[7.5px]
                                          bg-[#FD9B11] text-white font-bold text-base leading-5
                                          py-[8px] pl-[42px] pr-[55px] rounded-[28px]
                                          shadow-[0_0_23px_#fd9b11] hover:brightness-110
                                          transition-all duration-300 active:scale-95">
                                    подробнее
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </main>
    </div>
@endsection
