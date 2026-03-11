@extends('partner.partner')

@section('content')
    <div class="py-6 space-y-10">

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @php
                $stats = [
                    ['label' => 'Кол-во клиентов', 'value' => '350', 'color' => 'bg-indigo-600', 'shadow' => 'shadow-indigo-100'],
                    ['label' => 'Кол-во покупок', 'value' => '12 350', 'color' => 'bg-emerald-600', 'shadow' => 'shadow-emerald-100'],
                    ['label' => 'MAX покупка', 'value' => '125 500 ₸', 'color' => 'bg-blue-600', 'shadow' => 'shadow-blue-100'],
                    ['label' => 'Получили призов', 'value' => '120', 'color' => 'bg-amber-500', 'shadow' => 'shadow-amber-100'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="{{ $stat['color'] }} {{ $stat['shadow'] }} rounded-[2rem] p-6 text-white shadow-lg transition-transform hover:scale-105 duration-300">
                    <p class="text-[10px] uppercase font-black tracking-widest opacity-80 mb-2 leading-tight">
                        {!! str_replace('<br>', ' ', $stat['label']) !!}
                    </p>
                    <div class="text-2xl md:text-3xl font-black italic">{{ $stat['value'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex bg-gray-100 p-1.5 rounded-2xl w-full md:w-auto">
                <a href="#" class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-sm font-bold transition-all bg-white shadow-sm text-gray-900">
                    Пользователи
                </a>
                <a href="#" class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-sm font-bold transition-all text-gray-500 hover:text-gray-900">
                    Маркетинг
                </a>
            </div>

            <div class="flex flex-wrap justify-center gap-2">
                @foreach(['сегодня', 'месяц', 'за всё время', 'указать период'] as $filter)
                    <a href="#" class="px-4 py-2 rounded-full text-[11px] font-black uppercase tracking-wider transition-all {{ $loop->first ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-gray-400 border border-gray-100 hover:border-emerald-200 hover:text-emerald-600' }}">
                        {{ $filter }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <div class="relative max-w-md mx-auto md:mx-0 group">
                <input type="text"
                       class="w-full bg-white border-2 border-gray-50 rounded-2xl py-4 pl-14 pr-6 text-sm font-medium focus:border-emerald-500 focus:ring-0 transition-all shadow-sm group-hover:shadow-md"
                       placeholder="Начните вводить имя..">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-300 group-focus-within:text-emerald-500 transition-colors">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                @foreach($clients as $user)
                    <div class="bg-white rounded-[2.5rem] border border-gray-50 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden flex flex-col md:flex-row">

                        <div class="md:w-64 bg-gray-50/50 p-8 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-100 space-y-4">
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white">
                                <img src="/images/clients/logo.png" alt="avatar" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col w-full gap-2">
                                <a href="#" class="w-full py-2 text-center text-[10px] font-black uppercase tracking-tighter text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                                    реквизиты
                                </a>
                                <a href="#" class="w-full py-2 text-center text-[10px] font-black uppercase tracking-tighter text-emerald-600 bg-emerald-50 rounded-xl hover:bg-emerald-600 hover:text-white transition-all">
                                    отправить подарок
                                </a>
                            </div>
                        </div>

                        <div class="flex-1 p-8">
                            <div class="flex flex-col lg:flex-row justify-between items-start gap-6">
                                <div class="space-y-4">
                                    <h3 class="text-2xl font-black text-gray-900 leading-none">{{ $user['full_name'] }}</h3>

                                    <div class="inline-flex items-center gap-4 bg-white border border-gray-100 p-3 pr-6 rounded-2xl shadow-sm">
                                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                        <div>
                                            <p class="text-[9px] font-black text-gray-400 uppercase leading-none mb-1">На счету</p>
                                            <span class="text-lg font-black text-gray-800 italic">250 ₸</span>
                                        </div>
                                        <div class="ml-4 border-l pl-4 border-gray-100">
                                            <p class="text-[9px] font-black text-emerald-500 uppercase leading-none mb-1">Кэшбек</p>
                                            <span class="text-sm font-bold text-emerald-600">+350</span>
                                        </div>
                                    </div>

                                    <p class="text-xs font-bold text-gray-400 italic">
                                        Выиграл призов: <span class="text-indigo-600 font-black not-italic">1 259</span>
                                    </p>
                                </div>

                                <div class="w-full lg:w-72">
                                    <div class="bg-gray-900 rounded-3xl p-5 text-white relative overflow-hidden group">
                                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/5 rounded-full"></div>
                                        <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">История покупок</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-[9px] uppercase text-gray-500 font-bold mb-1">Кол-во</p>
                                                <div class="text-xl font-black">{{ $user['cnt'] }}</div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[9px] uppercase text-gray-500 font-bold mb-1">Сумма</p>
                                                <div class="text-xl font-black text-emerald-400">{{ number_format($user['sum'], 0, '.', ' ') }} <span class="text-xs">₸</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop

@push('partner_scripts')
    <script src="/js/clients.js"></script>
@endpush
