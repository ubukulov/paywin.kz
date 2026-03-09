{{-- КАРТОЧКА ТЕКУЩЕГО БАЛАНСА --}}
<div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-3xl font-extrabold">
                {{ number_format(Auth::user()->balance, 0, '.', ' ') }} ₸
            </div>
            <div class="text-xs opacity-70 mt-1 uppercase tracking-wider font-medium">
                Текущий баланс
            </div>
        </div>

        <button
            @click="modalOpen = true" {{-- Если используешь Alpine.js --}}
        id="openModal"
            class="bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-95">
            + Пополнить
        </button>
    </div>
</div>

{{-- СПИСОК ТРАНЗАКЦИЙ --}}
<div class="space-y-3">
    @forelse($transactions as $transaction)
        <div class="bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between border border-gray-50">

            <div class="flex items-center gap-3">
                {{-- Иконка: стрелка вниз для прихода, вверх для расхода --}}
                <div class="w-10 h-10 rounded-full flex items-center justify-center
                    {{ $transaction->amount > 0 ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }}">
                    <i class="fas {{ $transaction->amount > 0 ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                </div>

                <div>
                    <div class="text-sm font-bold text-gray-800">
                        {{-- Выводим описание или название типа --}}
                        {{ $transaction->description ?? $transaction->type_name }}
                    </div>
                    <div class="text-[10px] text-gray-400 font-medium">
                        {{ $transaction->created_at->format('d.m.Y H:i') }}
                    </div>
                </div>
            </div>

            <div class="text-right">
                {{-- Сумма: зеленая для плюса, красная для минуса --}}
                <div class="font-bold {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $transaction->amount > 0 ? '+' : '' }}{{ number_format($transaction->amount, 0, '.', ' ') }} ₸
                </div>
                {{-- Показываем остаток после этой операции --}}
                <div class="text-[10px] text-gray-400">
                    {{ number_format($transaction->balance_after, 0, '.', ' ') }} ₸
                </div>
            </div>

        </div>
    @empty
        <div class="text-center py-10">
            <img src="{{ asset('images/empty-wallet.png') }}" class="w-20 mx-auto opacity-20 mb-3">
            <p class="text-gray-400 text-sm">История операций пуста</p>
        </div>
    @endforelse

    {{-- Пагинация --}}
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
