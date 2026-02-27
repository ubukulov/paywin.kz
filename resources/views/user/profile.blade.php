@extends('user.user')

@section('content')
    <div class="max-w-xl mx-auto md:p-8 animate-fade-in">

        <a href="{{ route('user.settings') }}" class="group inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] hover:text-gray-800 transition-colors mb-8">
            <div class="w-8 h-8 flex items-center justify-center bg-white rounded-xl shadow-sm group-hover:shadow-md transition-all">
                <img src="/images/profile/back-btn.svg" class="w-4 h-4 opacity-50 group-hover:opacity-100 group-hover:-translate-x-0.5 transition-all" alt="">
            </div>
            Назад
        </a>

        <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-white p-8 md:p-10">

            <div class="mb-10 text-center md:text-left">
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">Профиль</h3>
                <p class="text-xs text-gray-400 mt-2">Обновите ваши персональные данные</p>
            </div>

            <form action="{{ route('user.setting.profileUpdate') }}" method="post" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="block px-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">ФИО</label>
                    <input type="text" value="{{ $user_profile->full_name }}" name="full_name" required
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium text-gray-700 shadow-inner">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block px-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Пол</label>
                        <div class="relative">
                            <select name="sex" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium text-gray-700 shadow-inner appearance-none cursor-pointer">
                                <option @if($user_profile->sex == 1) selected @endif value="1">Мужской</option>
                                <option @if($user_profile->sex == 2) selected @endif value="2">Женский</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none opacity-30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block px-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Дата рождения</label>
                        <input type="date" value="{{ $user_profile->birth_date }}" name="birth_date" required
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium text-gray-700 shadow-inner">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block px-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Телефон</label>
                    <input type="text" value="{{ $user_profile->phone }}" name="phone" required
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium text-gray-700 shadow-inner">
                </div>

                <div class="space-y-2">
                    <label class="block px-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Email</label>
                    <input type="email" value="{{ $user_profile->email }}" name="email" required
                           class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium text-gray-700 shadow-inner">
                </div>

                <div class="pt-6">
                    <button type="submit"
                            class="w-full py-4 bg-gray-900 text-white rounded-2xl font-bold text-sm uppercase tracking-[0.1em] hover:bg-black transition-all shadow-xl shadow-gray-200 active:scale-95">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="/js/profile.js"></script>

    <style>
        body { background-color: #f8fafc; }
        /* Кастомизация календаря для инпута даты */
        input[type="date"]::-webkit-calendar-picker-indicator {
            opacity: 0.4;
            cursor: pointer;
        }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@stop
