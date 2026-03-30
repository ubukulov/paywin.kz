<div id="rechargeModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="toggleRechargeModal()"></div>

    <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate__animated animate__zoomIn animate__faster">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-black text-gray-800">Пополнение</h3>
                <button onclick="toggleRechargeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <p class="text-sm text-gray-500 mb-6">Выберите сумму или введите свою для мгновенного пополнения баланса.</p>

            <div class="grid grid-cols-3 gap-3 mb-6">
                @foreach([1000, 2000, 5000, 10000, 20000, 50000] as $amount)
                    <button onclick="setAmount({{ $amount }})"
                            class="py-3 rounded-2xl border-2 border-gray-100 font-bold text-gray-700 hover:border-indigo-500 hover:text-indigo-600 transition-all active:scale-95">
                        {{ number_format($amount, 0, '', ' ') }}
                    </button>
                @endforeach
            </div>

            <div class="relative mb-8">
                <input type="number" id="customAmount" placeholder="Другая сумма"
                       class="w-full bg-gray-50 border-2 border-transparent rounded-2xl py-4 px-6 font-bold text-lg focus:bg-white focus:border-indigo-500 transition-all outline-none">
                <span class="absolute right-6 top-1/2 -translate-y-1/2 font-bold text-gray-400">₸</span>
            </div>

            <button id="payButton" onclick="startPayment()"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-3">
                <span>ПЕРЕЙТИ К ОПЛАТЕ</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>
