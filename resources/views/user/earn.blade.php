@extends('user.user')

@section('content')
    <div class="p-4 pb-20 space-y-8 bg-gray-50 min-h-screen">

        {{-- БЛОК 1: ДОСТУПНЫЕ ПРЕДЛОЖЕНИЯ --}}
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                    <span class="bg-orange-500 text-white p-2 rounded-lg shadow-orange-200 shadow-lg">🎁</span>
                    Выбери и заработай
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($shares as $share)
                    <div class="relative group">
                        {{-- Сама карточка-купон --}}
                        <div class="bg-white border-2 border-dashed border-gray-200 rounded-3xl p-6 transition-all duration-300 hover:border-orange-400 hover:shadow-xl relative overflow-hidden">

                            {{-- Декоративные вырезы купона --}}
                            <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gray-50 rounded-full border-r-2 border-dashed border-gray-200 group-hover:border-orange-400"></div>
                            <div class="absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gray-50 rounded-full border-l-2 border-dashed border-gray-200 group-hover:border-orange-400"></div>

                            <div class="flex flex-col gap-5">
                                {{-- Шапка карточки --}}
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest mb-1">Промокод партнера</p>
                                        <h3 class="text-3xl font-black text-gray-900 tracking-tight">{{ $share->title ?? 'Без названия' }}-{{ \Illuminate\Support\Facades\Auth::id() }}</h3>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-black shadow-sm">
                                            +{{ $share->real_agent_percent ?? 0 }}%
                                        </span>
                                        <span class="text-[9px] text-gray-400 uppercase mt-1 font-bold">твой доход</span>
                                    </div>
                                </div>

                                {{-- Ссылка и кнопка копирования --}}
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-400 uppercase ml-1">Персональная ссылка</label>
                                    <div class="relative flex items-center bg-gray-50 rounded-2xl border border-gray-100 p-1 group/link">
                                        <input type="text" readonly
                                               id="link-{{ $share->id }}"
                                               value="{{ route('user.referral.promocode', ['agent_id' => auth()->id(), 'promo_code' => $share->title]) }}-{{ \Illuminate\Support\Facades\Auth::id() }}"
                                               class="bg-transparent text-sm text-blue-500 font-mono px-3 py-2 w-full outline-none">

                                        <button onclick="copyLink('link-{{ $share->id }}', this)"
                                                class="bg-white text-gray-700 p-2.5 rounded-xl shadow-sm hover:bg-gray-900 hover:text-white transition-all active:scale-95 group-active/link:bg-green-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Прогресс и статы --}}
                                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold">Активаций</span>
                                            <span class="text-lg font-black text-gray-900">{{ $share->used_count }} <span class="text-gray-300 font-medium">/ {{ $share->count ?: '∞' }}</span></span>
                                        </div>
                                    </div>

                                    {{-- Визуальный индикатор лимита --}}
                                    @if($share->count > 0)
                                        <div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-orange-500 rounded-full" style="width: {{ ($share->used_count / $share->count) * 100 }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- Простой скрипт для копирования --}}
    <script>
        function copyLink(inputId, btn) {
            const copyText = document.getElementById(inputId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            const originalSvg = btn.innerHTML;
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>';
            btn.classList.remove('bg-white', 'text-gray-700');
            btn.classList.add('bg-green-500', 'text-white');

            setTimeout(() => {
                btn.innerHTML = originalSvg;
                btn.classList.remove('bg-green-500', 'text-white');
                btn.classList.add('bg-white', 'text-gray-700');
            }, 2000);
        }
    </script>
@stop
