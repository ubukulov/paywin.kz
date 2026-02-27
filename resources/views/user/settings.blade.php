@extends('user.user')

@section('content')
    <div class="max-w-4xl mx-auto p-2 md:p-8 animate-fade-in">
        <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-white">

            <div class="p-8 md:p-10 bg-gradient-to-r from-slate-50 to-gray-50/50 border-b border-gray-100">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="relative">
                        <div class="absolute -inset-1 bg-gradient-to-tr from-blue-400 to-purple-400 rounded-full blur opacity-20"></div>
                        <div class="relative w-24 h-24 rounded-full border-4 border-white shadow-lg overflow-hidden">
                            <img src="/images/profile/avatar.png" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <div class="text-center md:text-left flex-grow">
                        <h2 class="text-2xl font-black text-gray-800 tracking-tight">{{ $user_profile->full_name }}</h2>
                        <div class="flex items-center justify-center md:justify-start gap-4 mt-2">
                            <div class="flex items-center gap-1.5 text-sm font-medium text-gray-500">
                                @if($user_profile->sex == 1)
                                    <img src="/images/profile/male-icon.svg" class="w-4 h-4" alt="">
                                    <span>Мужской</span>
                                @elseif($user_profile->sex == 2)
                                    <img src="/images/profile/female.svg" class="w-4 h-4" alt="">
                                    <span>Женский</span>
                                @endif
                            </div>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <div class="text-sm font-bold text-blue-600">
                                Баланс: {{ Auth::user()->getBalanceForUser() }} ₸
                            </div>
                        </div>
                    </div>

                    <button type="button"
                            data-toggle="modal" data-target="#exampleModal"
                            class="px-6 py-3 bg-gray-900 text-white rounded-2xl font-bold text-sm hover:bg-black transition-all shadow-lg active:scale-95">
                        + пополнить
                    </button>
                </div>
            </div>

            <div class="p-4 md:p-8 space-y-8">

                <div>
                    <p class="px-4 mb-3 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Способы оплаты</p>
                    <a href="#" class="group flex items-center justify-between p-4 bg-gray-50/50 hover:bg-white hover:shadow-xl hover:shadow-gray-200/50 rounded-2xl transition-all duration-300 border border-transparent hover:border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-10 bg-white rounded-lg border border-gray-100 flex items-center justify-center shadow-sm">
                                <img src="/images/profile/master-card.svg" class="h-6" alt="">
                            </div>
                            <div class="flex gap-1 items-center">
                                <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                                <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                                <span class="w-1.5 h-1.5 bg-gray-300 rounded-full"></span>
                                <span class="text-gray-700 font-bold ml-1">2458</span>
                            </div>
                        </div>
                        <img src="/images/profile/right-arrow.svg" class="w-5 h-5 opacity-30 group-hover:opacity-100 group-hover:translate-x-1 transition-all" alt="">
                    </a>
                </div>

                <div class="space-y-2">
                    <p class="px-4 mb-3 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Настройки аккаунта</p>

                    <div class="grid grid-cols-1 gap-2">

                        {{--<a href="#" class="group flex items-center justify-between p-4 hover:bg-blue-50/50 rounded-2xl transition-all">
                            <span class="text-gray-700 font-medium">Поделиться приложением</span>
                            <img src="/images/profile/right-arrow.svg" class="w-4 h-4 opacity-20 group-hover:opacity-100 transition-all" alt="">
                        </a>--}}

                        <a href="{{ route('user.setting.passwordChangeForm') }}" class="group flex items-center justify-between p-4 hover:bg-blue-50/50 rounded-2xl transition-all">
                            <div class="flex flex-col">
                                <span class="text-gray-700 font-medium">Сменить пароль</span>
                                <span class="text-[10px] text-gray-400">Безопасность вашего аккаунта</span>
                            </div>
                            <img src="/images/profile/right-arrow.svg" class="w-4 h-4 opacity-20 group-hover:opacity-100 transition-all" alt="">
                        </a>

                        <a href="{{ route('user.setting.profile') }}" class="group flex items-center justify-between p-4 hover:bg-blue-50/50 rounded-2xl transition-all">
                            <span class="text-gray-700 font-medium">Редактировать профиль</span>
                            <img src="/images/profile/right-arrow.svg" class="w-4 h-4 opacity-20 group-hover:opacity-100 transition-all" alt="">
                        </a>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ route('logout') }}" class="group flex items-center justify-between p-4 bg-red-50 hover:bg-red-500 rounded-2xl transition-all duration-300">
                        <span class="text-red-600 font-bold group-hover:text-white transition-colors">Выйти из аккаунта</span>
                        <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center group-hover:bg-white/10">
                            <img src="/images/profile/right-arrow.svg" class="w-4 h-4 invert group-hover:brightness-0 group-hover:invert-0 transition-all" alt="">
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <p class="mt-8 text-center text-[10px] font-bold text-gray-300 uppercase tracking-widest">Version 2.0.4 — Build 2026</p>
    </div>

    <style>
        body { background-color: #f8fafc; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@endsection
