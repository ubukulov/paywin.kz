@extends('user.user')

@section('content')
    <div class="max-w-md mx-auto md:p-8 animate-fade-in">

        <a href="{{ route('user.settings') }}" class="group inline-flex items-center gap-2 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] hover:text-gray-800 transition-colors mb-8">
            <div class="w-8 h-8 flex items-center justify-center bg-white rounded-xl shadow-sm group-hover:shadow-md transition-all">
                <img src="/images/profile/back-btn.svg" class="w-4 h-4 opacity-50 group-hover:opacity-100 group-hover:-translate-x-0.5 transition-all" alt="">
            </div>
            Назад
        </a>

        <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-white p-8 md:p-10">

            <div class="mb-10 text-center md:text-left">
                <h3 class="text-2xl font-black text-gray-800 tracking-tight">Безопасность</h3>
                <p class="text-xs text-gray-400 mt-2">Обновите ваш пароль для защиты аккаунта</p>
            </div>

            <form action="{{ route('user.setting.passwordUpdate') }}" method="post" class="space-y-6">
                @csrf

                <div class="space-y-2">
                    <label class="block px-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Текущий пароль</label>
                    <div class="relative group">
                        <input type="password" name="password" required
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium text-gray-700 shadow-inner placeholder:text-gray-300"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block px-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Новый пароль</label>
                    <div class="relative group">
                        <input type="password" name="new_password" required
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium text-gray-700 shadow-inner placeholder:text-gray-300"
                               placeholder="Минимум 8 символов">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block px-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Подтверждение</label>
                    <div class="relative group">
                        <input type="password" name="confirm_new_password" required
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none font-medium text-gray-700 shadow-inner placeholder:text-gray-300"
                               placeholder="Повторите новый пароль">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="w-full py-4 bg-gray-900 text-white rounded-2xl font-bold text-sm uppercase tracking-[0.1em] hover:bg-black transition-all shadow-xl shadow-gray-200 active:scale-95">
                        Изменить пароль
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-8 text-center text-[10px] text-gray-300 leading-relaxed max-w-[280px] mx-auto uppercase tracking-wider font-medium">
            После смены пароля вам может потребоваться повторный вход в приложение
        </p>
    </div>

    <script src="{{ asset('js/profile.js') }}"></script>

    <style>
        body { background-color: #f8fafc; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
@stop
