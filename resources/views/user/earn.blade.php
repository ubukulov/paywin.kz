@extends('user.user')

@section('content')
    <div class="p-4 pb-20 space-y-6 bg-gray-50 min-h-screen">

        {{-- ШАПКА И ПЕРЕКЛЮЧАТЕЛЬ ТАБОВ --}}
        <section>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                    <span class="bg-orange-500 text-white p-2 rounded-lg shadow-orange-200 shadow-lg">🎁</span>
                    Выбери и заработай
                </h2>

                {{-- Кнопки Табов --}}
                <div class="flex bg-gray-200/80 p-1 rounded-2xl w-full sm:w-auto">
                    <button type="button" onclick="switchTab('promocodes')" id="tab-btn-promocodes"
                            class="flex-1 sm:flex-initial px-5 py-2.5 rounded-xl text-xs font-black transition-all duration-200 bg-white text-gray-900 shadow-sm">
                        🏷️ Промокоды
                    </button>
                    <button type="button" onclick="switchTab('purchases')" id="tab-btn-purchases"
                            class="flex-1 sm:flex-initial px-5 py-2.5 rounded-xl text-xs font-black transition-all duration-200 text-gray-500 hover:text-gray-900">
                        🛒 Покупки рефералов
                    </button>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- ТАБ 1: ПРОМОКОДЫ                            --}}
            {{-- ========================================== --}}
            <div id="tab-content-promocodes" class="space-y-6">
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
                                    {{-- Шапка купона --}}
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest mb-1">
                                                {{ $myPromo ? 'Твой личный код' : 'Акция партнера' }}
                                            </p>

                                            <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">
                                                {{ $myPromo ? $myPromo->code : ($share->code ?? 'Без кода') }}
                                            </h3>

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

                                        {{-- ФОРМА РЕДАКТИРОВАНИЯ --}}
                                        <div class="hidden bg-blue-50 rounded-2xl p-4 border border-blue-100" id="edit-mode-{{ $share->id }}">
                                            <form action="{{ route('user.promocode.update', $myPromo->id) }}" method="POST" class="space-y-3">
                                                @csrf
                                                @method('PUT')
                                                <div class="flex justify-between items-center">
                                                    <p class="text-[10px] font-bold text-blue-600 uppercase">Новый промокод:</p>
                                                    <button type="button" onclick="toggleEdit('{{ $share->id }}')" class="text-[10px] text-gray-400 font-bold uppercase">Отмена</button>
                                                </div>
                                                <div class="flex gap-2">
                                                    <input type="text" name="code" value="{{ $myPromo->code }}" required
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
                                            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold">
                                                {{ session('error') ?? 'Ошибка при заполнении. Проверьте код.' }}
                                            </div>
                                        @endif
                                        <div class="bg-orange-50 rounded-2xl p-4 border border-orange-100">
                                            <form action="{{ route('user.promocode.store') }}" method="POST" class="space-y-3">
                                                @csrf
                                                <input type="hidden" name="share_id" value="{{ $share->id }}">
                                                <p class="text-[10px] font-bold text-orange-600 uppercase">Придумай свой уникальный код:</p>
                                                <div class="flex gap-2">
                                                    <input type="text" name="code" autocorrect="off" autocapitalize="characters" placeholder="Напр: SUPER{{ auth()->id() }}" required
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
            </div>

            {{-- ========================================== --}}
            {{-- ТАБ 2: ПОКУПКИ РЕФЕРАЛОВ                   --}}
            {{-- ========================================== --}}
            <div id="tab-content-purchases" class="hidden space-y-4">
                <div class="bg-white rounded-3xl p-4 sm:p-6 shadow-sm border border-gray-100">

                    @if(isset($orders) && $orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                <tr class="border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                    <th class="pb-3 px-2">№ Заказа / Дата</th>
                                    <th class="pb-3 px-2">Клиент (Реферал)</th>
                                    <th class="pb-3 px-2">Сумма покупки</th>
                                    <th class="pb-3 px-2 text-center">Статус</th>
                                    <th class="pb-3 px-2 text-right">Вознаграждение</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-xs font-semibold">
                                @foreach($orders as $order)
                                    @php
                                        // 1. Ищем запись реферала для этого пользователя и агента
                                        $referral = \App\Models\Referral::where('agent_id', auth()->id())
                                            ->where('user_id', $order->user_id)
                                            ->first();

                                        // 2. Рассчитываем вознаграждение через метод getEarn() модели Referral (если запись найдена)
                                        $reward = 0;
                                    @endphp
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        {{-- Номер заказа и дата --}}
                                        <td class="py-4 px-2">
                                            <div class="font-black text-gray-900">#{{ $order->id }}</div>
                                            <div class="text-[10px] text-gray-400 font-medium">{{ $order->created_at->format('d.m.Y H:i') }}</div>
                                        </td>

                                        {{-- Имя клиента --}}
                                        <td class="py-4 px-2">
                                            <div class="font-bold text-gray-800">{{ $order->user->name ?? 'Клиент #' . $order->user_id }}</div>
                                            <div class="text-[10px] text-gray-400 font-medium">{{ $order->user->email ?? '' }}</div>
                                        </td>

                                        {{-- Сумма заказа --}}
                                        <td class="py-4 px-2 font-black text-gray-900">
                                            {{ number_format($order->total, 0, '.', ' ') }} ₸
                                        </td>

                                        {{-- Статус заказа --}}
                                        <td class="py-4 px-2 text-center">
                                            @php
                                                $statusValue = is_object($order->status) ? $order->status->value : $order->status;
                                            @endphp
                                            @if(in_array($statusValue, ['paid', 'completed', 'PAID', 'COMPLETED']))
                                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-[10px] font-black border border-green-200">
                                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                                        {{ $statusValue === 'completed' || $statusValue === 'COMPLETED' ? 'Выполнен' : 'Оплачен' }}
                                                    </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-gray-100 text-gray-600">
                                                        {{ $statusValue }}
                                                    </span>
                                            @endif
                                        </td>

                                        {{-- Вознаграждение агента --}}
                                        <td class="py-4 px-2 text-right">
                                            @if($reward > 0)
                                                <span class="text-sm font-black text-green-600">+{{ number_format($reward, 0, '.', ' ') }} ₸</span>
                                            @else
                                                <span class="text-xs font-bold text-gray-300">0 ₸</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Пагинация --}}
                        @if(method_exists($orders, 'links'))
                            <div class="mt-4">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @else
                        {{-- Пустое состояние --}}
                        <div class="text-center py-12">
                            <div class="text-4xl mb-3">🛒</div>
                            <h4 class="text-base font-bold text-gray-800">Покупок пока нет</h4>
                            <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                                Делись своими промокодами. Как только рефералы сделают покупки, они сразу отобразятся в этом списке!
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        </section>
    </div>

    {{-- Скрипт переключения Табов и копирования --}}
    <script>
        function switchTab(tabName) {
            const tabPromo = document.getElementById('tab-content-promocodes');
            const tabPurchases = document.getElementById('tab-content-purchases');
            const btnPromo = document.getElementById('tab-btn-promocodes');
            const btnPurchases = document.getElementById('tab-btn-purchases');

            if (tabName === 'promocodes') {
                tabPromo.classList.remove('hidden');
                tabPurchases.classList.add('hidden');

                // Стили активной кнопки
                btnPromo.className = "flex-1 sm:flex-initial px-5 py-2.5 rounded-xl text-xs font-black transition-all duration-200 bg-white text-gray-900 shadow-sm";
                btnPurchases.className = "flex-1 sm:flex-initial px-5 py-2.5 rounded-xl text-xs font-black transition-all duration-200 text-gray-500 hover:text-gray-900";
            } else {
                tabPromo.classList.add('hidden');
                tabPurchases.classList.remove('hidden');

                // Стили активной кнопки
                btnPurchases.className = "flex-1 sm:flex-initial px-5 py-2.5 rounded-xl text-xs font-black transition-all duration-200 bg-white text-gray-900 shadow-sm";
                btnPromo.className = "flex-1 sm:flex-initial px-5 py-2.5 rounded-xl text-xs font-black transition-all duration-200 text-gray-500 hover:text-gray-900";
            }
        }

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
