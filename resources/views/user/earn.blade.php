@extends('user.user')

@section('content')
    <div class="p-4 pb-20 space-y-8 bg-gray-50 min-h-screen">

        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                    <span class="bg-orange-500 text-white p-2 rounded-lg shadow-orange-200 shadow-lg">🎁</span>
                    Выбери и заработай
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($shares as $share)
                    @php
                        // Ищем, создал ли уже текущий агент свой код для этой акции
                        $myPromo = \App\Models\Promocode::where('agent_id', auth()->id())
                                    ->where('share_id', $share->id)
                                    ->first();
                    @endphp

                    <div class="relative group">
                        <div class="bg-white border-2 border-dashed border-gray-200 rounded-3xl p-6 transition-all duration-300 hover:border-orange-400 hover:shadow-xl relative overflow-hidden">

                            {{-- Вырезы купона --}}
                            <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gray-50 rounded-full border-r-2 border-dashed border-gray-200 group-hover:border-orange-400"></div>
                            <div class="absolute -right-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-gray-50 rounded-full border-l-2 border-dashed border-gray-200 group-hover:border-orange-400"></div>

                            <div class="flex flex-col gap-5">
                                {{-- Шапка --}}
                                <div class="flex justify-between items-start">
                                    <div>
                                        {{-- Если код создан, пишем "Твой личный код", если нет - "Акция партнера" --}}
                                        <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest mb-1">
                                            {{ $myPromo ? 'Твой личный код' : 'Акция партнера' }}
                                        </p>

                                        <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">
                                            {{ $myPromo ? $myPromo->code : ($share->code ?? 'Без кода') }}
                                        </h3>

                                        {{-- НОВОЕ: Показываем оригинальный код партнера, если у агента уже создан свой --}}
                                        @if($myPromo)
                                            <div class="mt-2 flex items-center gap-1.5">
                                                <span class="text-[9px] text-gray-400 uppercase font-bold tracking-tighter">Базовый код:</span>
                                                <span class="text-[10px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded font-mono font-bold">
                    {{ $share->code }}
                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-black shadow-sm">
                                            +{{ $share->real_agent_percent ?? 0 }}%
                                        </span>
                                        <span class="text-[9px] text-gray-400 uppercase mt-1 font-bold">твой доход</span>
                                    </div>
                                </div>

                                @if($myPromo)
                                    {{-- СОСТОЯНИЕ: КОД СОЗДАН --}}
                                    <div class="space-y-2" id="view-mode-{{ $share->id }}">
                                        <div class="flex justify-between">
                                            <label class="text-xs font-bold text-gray-400 uppercase ml-1">Твоя персональная ссылка</label>
                                            {{-- Кнопка переключения в режим редактирования --}}
                                            <button onclick="toggleEdit('{{ $share->id }}')" class="text-[10px] font-bold text-blue-500 hover:text-blue-700 uppercase">
                                                Изменить код
                                            </button>
                                        </div>
                                        <div class="relative flex items-center bg-gray-50 rounded-2xl border border-gray-100 p-1 group/link">
                                            <input type="text" readonly
                                                   id="link-{{ $myPromo->id }}"
                                                   value="{{ route('user.referral.promocode', ['agent_id' => auth()->id(), 'promo_code' => $myPromo->code]) }}"
                                                   class="bg-transparent text-[11px] text-blue-500 font-mono px-3 py-2 w-full outline-none">

                                            <button onclick="copyLink('link-{{ $myPromo->id }}', this)"
                                                    class="bg-white text-gray-700 p-2.5 rounded-xl shadow-sm hover:bg-gray-900 hover:text-white transition-all active:scale-95">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- ФОРМА РЕДАКТИРОВАНИЯ (по умолчанию скрыта) --}}
                                    <div class="hidden bg-blue-50 rounded-2xl p-4 border border-blue-100" id="edit-mode-{{ $share->id }}">
                                        <form action="{{ route('user.promocode.update', $myPromo->id) }}" method="POST" class="space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="flex justify-between items-center">
                                                <p class="text-[10px] font-bold text-blue-600 uppercase">Новый промокод:</p>
                                                <button type="button" onclick="toggleEdit('{{ $share->id }}')" class="text-[10px] text-gray-400 font-bold uppercase">Отмена</button>
                                            </div>
                                            <div class="flex gap-2">
                                                <input type="text" name="code" value="{{ $myPromo->code }}"
                                                       required
                                                       class="flex-1 bg-white border border-blue-200 rounded-xl px-3 py-2 text-sm font-bold uppercase focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-600 transition-colors">
                                                    Сохранить
                                                </button>
                                            </div>
                                            <p class="text-[9px] text-blue-400 leading-tight">Внимание: старая ссылка перестанет работать сразу после смены кода.</p>
                                        </form>
                                    </div>
                                @else
                                    {{-- СОСТОЯНИЕ: НУЖНО СОЗДАТЬ КОД --}}
                                    @if(session('error') || $errors->any())
                                        <div class="mx-4 mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold animate__animated animate__fadeIn">
                                            {{ session('error') ?? 'Ошибка при заполнении. Проверьте код.' }}
                                            <ul class="text-xs mt-1 font-medium">
                                                @foreach($errors->all() as $error)
                                                    <li>• {{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="bg-orange-50 rounded-2xl p-4 border border-orange-100">
                                        <form action="{{ route('user.promocode.store') }}" method="POST" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="share_id" value="{{ $share->id }}">
                                            <p class="text-[10px] font-bold text-orange-600 uppercase">Придумай свой уникальный код:</p>
                                            <div class="flex gap-2">
                                                <input type="text" name="code" autocorrect="off" autocapitalize="characters" placeholder="Напр: SUPER{{ auth()->id() }}"
                                                       required
                                                       class="flex-1 bg-white border border-orange-200 rounded-xl px-3 py-2 text-sm font-bold uppercase focus:outline-none focus:ring-2 focus:ring-orange-500">
                                                <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-orange-600 transition-colors">
                                                    ОК
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                {{-- Прогресс --}}
                                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-400 uppercase font-bold">Активаций этой акции</span>
                                        <span class="text-lg font-black text-gray-900">{{ $share->used_count }} <span class="text-gray-300 font-medium">/ {{ $share->count ?: '∞' }}</span></span>
                                    </div>

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

    <script>
        function copyLink(inputId, btn) {
            const copyText = document.getElementById(inputId);
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            const originalSvg = btn.innerHTML;
            btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>';
            btn.classList.replace('bg-white', 'bg-green-500');
            btn.classList.replace('text-gray-700', 'text-white');

            setTimeout(() => {
                btn.innerHTML = originalSvg;
                btn.classList.replace('bg-green-500', 'bg-white');
                btn.classList.replace('text-white', 'text-gray-700');
            }, 2000);
        }

        function toggleEdit(shareId) {
            const viewDiv = document.getElementById(`view-mode-${shareId}`);
            const editDiv = document.getElementById(`edit-mode-${shareId}`);

            viewDiv.classList.toggle('hidden');
            editDiv.classList.toggle('hidden');
        }

    </script>
@stop
