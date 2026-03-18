@extends('partner.partner')

@section('content')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="{{ asset('css/swiper-boundle.min.css') }}">

    <div class="md:p-8 max-w-7xl mx-auto font-sans text-gray-800">

        <div class="relative bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/60 border border-gray-100 overflow-hidden mb-10">

            <div class="h-32 bg-gradient-to-r from-gray-900 via-indigo-950 to-gray-900 w-full relative">
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"30\" height=\"30\" viewBox=\"0 0 30 30\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M15 0L30 15L15 30L0 15L15 0Z\" fill=\"%23ffffff\" fill-opacity=\"0.4\"/%3E%3C/svg%3E');"></div>
        </div>

        <div class="px-6 pb-10 -mt-20 flex flex-col items-center relative z-10">
            <div class="w-32 h-32 md:w-44 md:h-44 rounded-[2.5rem] overflow-hidden border-[6px] border-white shadow-2xl bg-white flex items-center justify-center transition-transform duration-500 hover:scale-105">
                <img src="{{ empty($partnerProfile->logo) ? '/images/cabinet/papa-johns-pizza.svg' : $partnerProfile->logo }}"
                     alt="logo" class="max-w-full h-auto object-contain p-4">
            </div>

            <div class="text-center mt-6 w-full max-w-4xl">
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 tracking-tight mb-8">
                    {{ $partnerProfile->company }}
                </h1>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8 w-full max-w-4xl mx-auto">

                    <div class="bg-indigo-600 rounded-[2rem] p-6 text-white shadow-lg shadow-indigo-100 flex flex-col items-center justify-center relative overflow-hidden group">
                        <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest mb-1">Ваш баланс</p>
                        <div class="text-2xl font-black mb-4">{{ number_format($partner->getBalanceAttribute(), 0, '.', ' ') }} ₸</div>
                        <a href="{{ route('partner.payouts.index') }}"
                           class="relative z-10 flex items-center justify-center w-full bg-white text-indigo-600 text-[11px] font-black uppercase py-3 rounded-xl hover:bg-indigo-50 transition-all shadow-sm active:scale-95">
                            Вывести
                        </a>
                    </div>

                    <div class="bg-orange-50 rounded-[2rem] p-6 border border-orange-100 flex flex-col items-center justify-center group">
                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <img src="/images/cabinet/persent-icon.svg" alt="icon" class="w-5 h-5">
                        </div>
                        <p class="text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-1">Активный тариф</p>
                        <div class="text-3xl font-black text-orange-600 leading-none">
                            {{$partnerProfile->percent}}%
                        </div>
                        <p class="text-[10px] text-orange-400/80 mt-2 font-medium">комиссия сервиса</p>
                    </div>

                    <div class="bg-gray-50 rounded-[2rem] p-6 border border-gray-100 flex flex-col items-center justify-center">
                        <div class="flex items-center gap-2 text-green-500 font-black text-lg mb-1">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                            Активен
                        </div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Статус аккаунта</p>
                        <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div class="bg-green-500 h-full w-full"></div>
                        </div>
                        <p class="text-[9px] text-gray-400 mt-2">Профиль подтвержден</p>
                    </div>
                </div>

                <div class="flex flex-wrap justify-center items-center gap-4 mt-12 pt-8 border-t border-gray-50">
                    <a href="{{ route('partner.edit') }}"
                       class="group flex items-center gap-3 px-8 py-4 bg-gray-950 text-white rounded-2xl font-bold hover:bg-black transition-all shadow-xl shadow-gray-200 active:scale-95">
                        <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center group-hover:bg-white/20 transition-colors">
                            <img src="/images/cabinet/edit-icon.svg" alt="icon" class="w-4 h-4 brightness-0 invert">
                        </div>
                        Настройки
                    </a>

                    @if(!empty($partnerProfile->agreement))
                        <a href="{{$partnerProfile->getAgreementUrl()}}" target="_blank"
                           class="flex items-center gap-3 px-8 py-4 bg-white text-gray-700 rounded-2xl font-bold border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all active:scale-95">
                            <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center">
                                <img src="/images/cabinet/add-file.svg" alt="icon" class="w-4 h-4 opacity-60">
                            </div>
                            Договор
                        </a>
                    @endif

                    <a href="{{ route('logout') }}"
                       class="px-6 py-4 text-sm font-bold text-red-500 hover:text-red-700 transition-colors">
                        Выйти
                    </a>
                </div>
            </div>
        </div>
    </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50">
                    <h3 class="text-lg font-bold mb-6">Контакты</h3>
                    <div class="space-y-4">
                        @php
                            $contacts = [
                                ['icon' => 'phone-icon.svg', 'text' => $partnerProfile->phone, 'link' => '#'],
                                ['icon' => 'location.svg', 'text' => $partnerProfile->address, 'link' => null],
                                ['icon' => 'mail.svg', 'text' => $partnerProfile->email, 'link' => '#'],
                                ['icon' => 'website-icon.svg', 'text' => $partnerProfile->site, 'link' => '#'],
                            ];
                        @endphp

                        @foreach($contacts as $contact)
                            <div class="flex items-start gap-4">
                                <div
                                    class="w-10 h-10 flex-shrink-0 bg-gray-50 rounded-full flex items-center justify-center">
                                    <img src="/images/cabinet/{{ $contact['icon'] }}" class="w-5 h-5">
                                </div>
                                @if($contact['link'])
                                    <a href="{{ $contact['link'] }}"
                                       class="text-sm text-gray-600 hover:text-blue-600 mt-2">{{ $contact['text'] }}</a>
                                @else
                                    <p class="text-sm text-gray-600 mt-2">{{ $contact['text'] }}</p>
                                @endif
                            </div>
                        @endforeach

                        <div class="flex items-start gap-4">
                            <div
                                class="w-10 h-10 flex-shrink-0 bg-gray-50 rounded-full flex items-center justify-center">
                                <img src="/images/cabinet/clock-graphic.svg" class="w-5 h-5">
                            </div>
                            <div class="text-sm text-gray-600 mt-2 leading-relaxed">
                                {!! $partnerProfile->work_time !!}
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t">
                            <div class="w-10 h-10 bg-yellow-50 rounded-full flex items-center justify-center">
                                <img src="/images/cabinet/rating-star.svg" class="w-5 h-5">
                            </div>
                            <p class="text-lg font-bold">4.6 <span
                                    class="text-gray-400 font-normal text-sm">/ 5.0</span></p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-50 bg-gradient-to-br from-white to-gray-50">
                    <h3 class="text-lg font-bold mb-4">Реквизиты</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                                <img src="/images/cabinet/bank-name.svg" class="w-4 h-4"> Название банка
                            </label>
                            <input value="{{ $partnerProfile->bank_name }}" disabled
                                   class="w-full bg-gray-100 border-none rounded-lg px-4 py-2 text-sm text-gray-700">
                        </div>
                        <div>
                            <label class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                                <img src="/images/cabinet/bank-account.svg" class="w-4 h-4"> Банковский счёт
                            </label>
                            <input value="{{ $partnerProfile->bank_account }}" disabled
                                   class="w-full bg-gray-100 border-none rounded-lg px-4 py-2 text-sm font-mono text-gray-700">
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-50">
                    <h3 class="text-xl font-bold mb-4">Описание</h3>
                    <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed">
                        @if(empty($partnerProfile->description))
                            <p class="italic text-gray-400">К сожалению, тут еще ничего не заполнено :(</p>
                        @else
                            {{ $partnerProfile->description }}
                        @endif
                    </div>
                </div>

                <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-50">
                    @include('partner.partials._address')
                </div>

                <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-50">
                    @include('partner.partials._images')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/swiper-boundle.min.js') }}"></script>
    <script>
        // Инициализация Swiper (настройки Tailwind не влияют на JS логику)
        const commonConfig = {
            slidesPerView: 1,
            spaceBetween: 20,
        };

        new Swiper(".partdescr__header-slider", {
            ...commonConfig,
            pagination: {el: ".swiper-pagination", type: "fraction"},
        });
        new Swiper(".partdescr__gift-slider", {
            ...commonConfig,
            pagination: {el: ".swiper-pagination"},
        });
        new Swiper(".partdescr__address-slider", {
            ...commonConfig,
            pagination: {el: ".swiper-pagination"},
        });
    </script>
@stop
