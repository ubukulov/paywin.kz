{{-- BALANCE CARD --}}
<div class="bg-white rounded-2xl shadow p-4 mb-5">

    <div class="flex items-center justify-between">

        <div>
            <div class="text-2xl font-bold text-gray-900">
                {{ Auth::user()->getBalanceForUser() }} ₸
            </div>
            <div class="text-xs text-gray-400">
                Текущий баланс
            </div>
        </div>

        <button
            id="openModal"
            class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transition">
            + пополнить
        </button>

    </div>

</div>



{{-- HISTORY --}}
<div class="space-y-3">

    @foreach(Auth::user()->getUserBalances as $balance)

        <div class="bg-white rounded-xl shadow-sm p-4 flex items-center justify-between">

            <div>
                <div class="text-xs text-gray-400">
                    {{ $balance->updated_at->format('d.m.Y H:i:s') }}
                </div>

                <div class="text-sm font-medium">
                    @if($balance->status == 'ok')
                        Пополнение
                    @elseif($balance->status == 'withdraw')
                        Вывод
                    @endif
                </div>
            </div>

            <div class="font-semibold
            {{ $balance->status == 'ok' ? 'text-green-600' : 'text-red-600' }}">
                {{ $balance->status == 'ok' ? '+' : '-' }} {{ $balance->amount }} ₸
            </div>

        </div>

    @endforeach

</div>

{{-- MODAL --}}
<div id="modal"
     class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white w-80 rounded-2xl p-5 shadow-xl">

        <h2 class="text-lg font-semibold mb-4">
            Пополнение баланса
        </h2>

        <form action="{{ route('user.balanceReplenishment') }}" method="post">
            @csrf

            <input
                type="number"
                name="amount"
                placeholder="Введите сумму"
                required
                class="w-full border rounded-lg px-3 py-2 mb-4 focus:ring-2 focus:ring-blue-500 outline-none"
            >

            <div class="flex gap-2 justify-end">

                <button type="button"
                        id="closeModal"
                        class="px-3 py-2 text-gray-500">
                    Отмена
                </button>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Продолжить
                </button>

            </div>
        </form>

    </div>
</div>

@push('user_scripts')
    <script>
        const modal = document.getElementById('modal')
        const openBtn = document.getElementById('openModal')
        const closeBtn = document.getElementById('closeModal')

        openBtn.onclick = () => modal.classList.remove('hidden')
        closeBtn.onclick = () => modal.classList.add('hidden')

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden')
            }
        })
    </script>
@endpush
