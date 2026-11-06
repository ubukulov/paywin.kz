@auth
    <div class="mt-6 p-4 bg-red-50/60 border border-red-200 rounded-2xl shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-red-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">K</span>
                <h4 class="font-semibold text-gray-900 text-sm">Уже купили этот товар в Kaspi?</h4>
            </div>
            <span class="text-[11px] text-red-600 text-center font-medium bg-red-100 px-2 py-0.5 rounded-full">Для бонуса</span>
        </div>

        <p class="text-xs text-gray-600 mb-3">
            Укажите номер заказа из приложения Kaspi (например, <code class="bg-white px-1.5 py-0.5 rounded border border-red-200 font-mono text-red-600">1105577907</code>), чтобы зарегистрировать покупку на Paywin.
        </p>

        <form id="kaspi-claim-form" class="flex gap-2">
            @csrf
            <input
                type="text"
                name="kaspi_code"
                id="kaspi_code_input"
                placeholder="Номер заказа Kaspi"
                required
                pattern="[0-9]{8,12}"
                class="flex-1 px-3 py-2 text-sm bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition"
            >
            <button
                type="submit"
                id="kaspi-submit-btn"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-xl whitespace-nowrap transition flex items-center gap-1 shadow-xs active:scale-98"
            >
                <span>Привязать</span>
            </button>
        </form>

        <div id="kaspi-feedback" class="mt-2 text-xs hidden"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('kaspi-claim-form');
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const input = document.getElementById('kaspi_code_input');
                const btn = document.getElementById('kaspi-submit-btn');
                const feedback = document.getElementById('kaspi-feedback');
                const code = input.value.trim();

                feedback.classList.add('hidden');
                feedback.className = 'mt-2 text-xs';
                btn.disabled = true;
                btn.classList.add('opacity-75');

                try {
                    const response = await fetch("{{ route('orders.claim-kaspi') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ kaspi_code: code })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        feedback.classList.add('text-green-600', 'font-semibold');
                        feedback.innerText = '✓ ' + data.message;
                        input.value = '';
                        if (typeof window.showToast === 'function') {
                            window.showToast(data.message);
                        }
                    } else {
                        feedback.classList.add('text-red-600');
                        feedback.innerText = '⚠ ' + (data.message || 'Ошибка проверки заказа');
                        if (typeof window.showToast === 'function') {
                            window.showToast(data.message || 'Ошибка проверки заказа', 'error');
                        }
                    }
                } catch (err) {
                    feedback.classList.add('text-red-600');
                    feedback.innerText = '⚠ Ошибка подключения к серверу';
                } finally {
                    feedback.classList.remove('hidden');
                    btn.disabled = false;
                    btn.classList.remove('opacity-75');
                }
            });
        });
    </script>
@else
    <div class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-2xl">
        <div class="flex items-center gap-2 mb-1">
            <span class="w-6 h-6 rounded-full bg-red-600 text-white font-bold text-xs flex items-center justify-center">K</span>
            <h4 class="font-semibold text-gray-800 text-sm">Купили этот товар в Kaspi?</h4>
        </div>
        <p class="text-xs text-gray-600">
            <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:underline">Авторизуйтесь</a>, чтобы ввести номер заказа из Kaspi и получить гарантированный бонус!
        </p>
    </div>
@endauth
