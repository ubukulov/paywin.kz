@extends('user.user')

@section('content')
    <div class="max-w-5xl mx-auto md:p-8">
        <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-white">
            <div class="flex flex-col lg:flex-row">

                <div class="lg:w-1/3 bg-gradient-to-b from-slate-50 to-gray-100/50 p-8 border-r border-gray-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative group">
                            <div class="absolute -inset-1 bg-gradient-to-tr from-blue-600 to-purple-500 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                            <div class="relative w-32 h-32 rounded-full border-4 border-white shadow-xl overflow-hidden">
                                <img src="{{ asset('images/profile/no-image.png') }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <h2 class="mt-4 text-xl font-bold text-gray-800">{{ $userProfile->full_name }}</h2>
                        <p class="text-sm text-gray-500">ID: {{ $user->id }}</p>

                        <div class="w-full mt-8 relative overflow-hidden bg-gray-900 rounded-3xl p-6 text-white shadow-2xl">
                            <div class="relative z-10">
                                <div class="flex justify-between items-start">
                                    <img src="/images/profile/wallet.svg" alt="" class="w-8 h-8 brightness-0 invert opacity-80">
                                    <button type="button"
                                            data-toggle="modal" data-target="#exampleModal"
                                            class="bg-white/20 hover:bg-white/30 backdrop-blur-md h-8 w-8 rounded-full flex items-center justify-center transition-all">
                                        <span class="text-xl leading-none">+</span>
                                    </button>
                                </div>
                                <p class="mt-4 text-xs text-gray-400 uppercase tracking-widest">Текущий баланс</p>
                                <p class="text-3xl font-bold mt-1 tabular-nums">{{ $user->getBalanceAttribute() }} <span class="text-lg font-light">₸</span></p>
                            </div>
                            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/20 rounded-full blur-2xl"></div>
                        </div>
                    </div>

                    <div class="mt-8 space-y-4">
                        <div class="flex items-center justify-between px-2">
                            <span class="text-sm font-bold text-gray-700 uppercase tracking-wider">Мои карты</span>
                            <a href="#" class="text-xs text-blue-600 font-semibold hover:underline">+ добавить</a>
                        </div>

                        @if(0)
                            <div class="bg-white border border-gray-200 rounded-2xl p-3 shadow-sm">
                                <select name="card_id" class="w-full bg-transparent text-sm font-medium text-gray-700 focus:outline-none cursor-pointer">
                                    @foreach($user->getMyCards() as $card)
                                        <option value="{{ $card['id'] }}">💳 {{ $card['number'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="text-center p-4 border-2 border-dashed border-gray-200 rounded-2xl">
                                <p class="text-xs text-gray-400">Нет привязанных карт</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:w-2/3 p-3 md:p-12 relative">
                    <a href="{{ route('user.settings') }}" class="absolute top-6 right-6 group">
                        <div class="p-3 bg-gray-50 rounded-2xl group-hover:bg-blue-50 transition-colors">
                            <img src="/images/profile/settings.svg" alt="Settings" class="w-6 h-6 transition-transform group-hover:rotate-90">
                        </div>
                    </a>

                    <h3 class="text-2xl font-bold text-gray-800 mb-8">Личные данные</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 flex items-center justify-center bg-orange-50 rounded-xl text-orange-500 group-hover:scale-110 transition-transform">
                                <img src="/images/profile/{{ $userProfile->sex == 1 ? 'female.svg' : 'male-icon.svg' }}" class="w-5 h-5" alt="">
                            </div>
                            <div>
                                <p class="text-[10px] uppercase text-gray-400 font-bold leading-none mb-1">Пол</p>
                                <p class="text-gray-700 font-medium">{{ $userProfile->sex == 1 ? 'Мужской' : 'Женский' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 flex items-center justify-center bg-blue-50 rounded-xl text-blue-500 group-hover:scale-110 transition-transform">
                                <img src="/images/profile/birthday-cake.svg" class="w-5 h-5" alt="">
                            </div>
                            <div>
                                <p class="text-[10px] uppercase text-gray-400 font-bold leading-none mb-1">Дата рождения</p>
                                <p class="text-gray-700 font-medium">{{ $userProfile->birth_date }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 flex items-center justify-center bg-green-50 rounded-xl text-green-500 group-hover:scale-110 transition-transform">
                                <img src="/images/profile/mobile-phone.svg" class="w-5 h-5" alt="">
                            </div>
                            <div>
                                <p class="text-[10px] uppercase text-gray-400 font-bold leading-none mb-1">Телефон</p>
                                <p class="text-gray-700 font-medium">{{ $userProfile->phone }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 flex items-center justify-center bg-purple-50 rounded-xl text-purple-500 group-hover:scale-110 transition-transform">
                                <img src="/images/profile/mail.svg" class="w-5 h-5" alt="">
                            </div>
                            <div>
                                <p class="text-[10px] uppercase text-gray-400 font-bold leading-none mb-1">Email</p>
                                <p class="text-gray-700 font-medium truncate max-w-[150px]">{{ $userProfile->email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3 truncate">
                            <i class="fa fa-link text-slate-400"></i>
                            <span class="text-xs font-mono text-slate-500 truncate">{{ route('user.referral.link', ['agent_id' => $user->id]) }}</span>
                        </div>
                        <button
                            onclick="copyText(this, '{{ route('user.referral.link', ['agent_id' => $user->id]) }}')"
                            class="text-[10px] font-bold text-blue-600 uppercase hover:text-blue-800 transition-all duration-300">
                            Копировать
                        </button>
                    </div>

                    <div class="mt-10">

                        @if(session('success'))
                            <div class="mb-4 p-4 rounded-2xl bg-green-50 border border-green-100 text-green-700 flex items-center gap-3 animate-fade-in">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="font-medium text-sm">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error') || $errors->has('promo_code'))
                            <div class="mb-4 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-700 flex items-center gap-3 animate-fade-in">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <span class="font-medium text-sm">
                                    {{ session('error') ?? $errors->first('promo_code') }}
                                </span>
                            </div>
                        @endif

                        <form action="{{ route('user.promoActivate') }}" method="post" class="relative group">
                            @csrf
                            <input type="text" name="promo_code" required placeholder="Введите промокод"
                                   class="w-full uppercase bg-gray-50 border-none rounded-2xl py-4 pl-6 pr-32 focus:ring-2 focus:ring-blue-500/20 transition-all placeholder:text-gray-400 font-medium text-gray-700 shadow-inner">
                            <div class="absolute right-2 top-2 flex items-center gap-2">
                                <a href="#" class="p-2 hover:bg-white rounded-lg transition-colors">
                                    <img src="/images/profile/scanner-promocode-icon.svg" alt="" class="w-5 h-5 opacity-40">
                                </a>
                                <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-black transition-all shadow-lg active:scale-95">
                                    OK
                                </button>
                            </div>
                        </form>
                    </div>

                    @if($prize)
                        @php
                            $share = $prize->share;
                            $partnerProfile = $share->partner->partnerProfile;
                        @endphp
                        <div class="mt-12 bg-gradient-to-br from-indigo-600 to-violet-700 rounded-[2rem] p-6 text-white shadow-xl shadow-indigo-200 relative overflow-hidden group">
                            <div class="relative z-10 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <div class="flex items-center gap-4 text-center sm:text-left">
                                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center p-2 shadow-inner">
                                        <img src="/images/profile/logo.png" alt="logo" class="max-w-full">
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg leading-tight">Сюрприз для вас!</h4>
                                        <p class="text-sm text-indigo-100 opacity-80">{{ $partnerProfile->company }} • {{ $share->title }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('user.getMyPrize', ['prize_id' => $prize->id]) }}"
                                   class="bg-white text-indigo-600 px-8 py-3 rounded-2xl font-black text-sm uppercase tracking-wider hover:bg-indigo-50 transition-all shadow-xl active:translate-y-1">
                                    Получить
                                </a>
                            </div>
                            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-12 flex flex-col md:flex-row items-center justify-center gap-4 text-gray-400">
            <div class="flex -space-x-2">
                <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-[10px]">🛡️</div>
                <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-[10px]">🔒</div>
            </div>
            <p class="text-xs text-center max-w-sm">
                Ваши данные защищены банковским уровнем шифрования (SSL). Мы не передаем информацию третьим лицам.
            </p>
        </div>
    </div>

    <style>
        /* Плавное появление */
        body { background-color: #f8fafc; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        function copyText(btn, text) {
            // Копирование в буфер обмена
            navigator.clipboard.writeText(text).then(() => {
                // Сохраняем старый текст
                const originalText = btn.innerText;

                // Меняем состояние кнопки
                btn.innerText = 'Скопировано!';
                btn.classList.replace('text-blue-600', 'text-green-600');

                // Возвращаем как было через 2 секунды
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.classList.replace('text-green-600', 'text-blue-600');
                }, 2000);
            }).catch(err => {
                console.error('Ошибка при копировании: ', err);
            });
        }
    </script>
@stop
