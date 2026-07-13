@extends('layouts.app')

@section('content')
    <script src="https://checkout.tiptoppay.kz/checkout.js"></script>
    <div class="max-w-4xl mx-auto px-4 py-10">

        <h1 class="text-3xl font-bold mb-8 text-gray-800">
            {{ $isInstant ? 'Экспресс-оформление заказа' : 'Оформление заказа' }}
        </h1>

        @if($isInstant)
            <div class="mb-8 p-5 rounded-xl bg-indigo-50 border border-indigo-100 shadow-sm flex items-center gap-4">
                <div class="text-3xl">⚡</div>
                <div>
                    <h3 class="font-bold text-indigo-900 text-lg">Покупка в 1 клик</h3>
                    <p class="text-indigo-700">Вы оформляете только этот товар. Отложенные ранее товары в вашей корзине останутся в сохранности.</p>
                </div>
            </div>
        @endif

        {{-- Модальное окно 3D Secure --}}
        <div id="threeDsModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;z-index:9999;">
            <div style="position:relative;width:400px;height:600px;background:#fff;border-radius:8px;overflow:hidden;">
                <iframe id="threeDsFrame" name="threeDsFrame" width="100%" height="100%"></iframe>
                <button id="close3ds" style="position:absolute;top:10px;right:10px; background: #eee; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">✖</button>
            </div>
        </div>

        <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
            @csrf

            {{-- Левая колонка --}}
            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Данные покупателя</h2>
                    <label class="block mb-4">
                        <span class="text-sm font-medium text-gray-700">Имя</span>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required
                               class="w-full border rounded-lg p-3 mt-1 focus:ring-indigo-500 focus:border-indigo-500">
                    </label>
                    <label class="block mb-4">
                        <span class="text-sm font-medium text-gray-700">Телефон</span>
                        <input type="text" name="phone" value="{{ auth()->user()->phone }}" required
                               class="w-full border rounded-lg p-3 mt-1 focus:ring-indigo-500 focus:border-indigo-500">
                    </label>
                    <label class="block mb-4">
                        <span class="text-sm font-medium text-gray-700">Адрес доставки</span>
                        <textarea name="address" rows="3" required
                                  class="w-full border rounded-lg p-3 mt-1 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </label>
                </div>

                {{-- УРОВЕНЬ 1: Персональные купоны/скидки (UserDiscount) --}}
                @if(isset($availableDiscounts) && count($availableDiscounts) > 0)
                    <div class="p-4 border border-indigo-100 bg-indigo-50/20 rounded-xl space-y-3">
                        <h3 class="font-bold text-indigo-900 text-xs uppercase tracking-wider">1. Скидочные купоны магазинов</h3>
                        @foreach($availableDiscounts as $disc)
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="applied_discounts[]" value="{{ $disc['discount_id'] }}"
                                           data-amount="{{ $disc['calculated_amount'] }}" class="partner-discount-checkbox w-5 h-5 accent-indigo-600 rounded cursor-pointer">
                                    <label class="text-sm text-gray-700 cursor-pointer select-none">
                                        <span class="font-black text-gray-900 block">{{ $disc['title'] }}</span>
                                        <span class="text-xs text-gray-400">Магазин: {{ $disc['partner_name'] }}</span>
                                    </label>
                                </div>
                                <span class="text-sm font-black text-indigo-600">- {{ number_format($disc['calculated_amount'], 0, '.', ' ') }} ₸</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- УРОВЕНЬ 2: Целевой Кэшбэк/Бонусы конкретных партнеров --}}
                @if(isset($partnerCashbacks) && count($partnerCashbacks) > 0)
                    <div class="p-4 border border-emerald-100 bg-emerald-50/20 rounded-xl space-y-3">
                        <h3 class="font-bold text-emerald-900 text-xs uppercase tracking-wider">2. Доступные бонусы партнёров</h3>
                        @foreach($partnerCashbacks as $cb)
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="use_partner_cashbacks[]" value="{{ $cb['partner_id'] }}"
                                           data-amount="{{ $cb['max_spendable'] }}" class="partner-cashback-checkbox w-5 h-5 accent-emerald-600 rounded cursor-pointer">
                                    <label class="text-sm text-gray-700 cursor-pointer select-none">
                                        <span class="font-black text-gray-900 block">Потратить бонусы магазина</span>
                                        <span class="text-xs text-gray-400">Доступно от "{{ $cb['partner_name'] }}": {{ number_format($cb['balance'], 0, '.', ' ') }} ₸</span>
                                    </label>
                                </div>
                                <span class="text-sm font-black text-emerald-600">- {{ number_format($cb['max_spendable'], 0, '.', ' ') }} ₸</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- УРОВЕНЬ 3: Глобальный пополненный общий баланс --}}
                @if($globalBalance > 0)
                    <div class="p-4 border border-blue-100 bg-blue-50/20 rounded-xl space-y-3">
                        <h3 class="font-bold text-blue-900 text-xs uppercase tracking-wider">3. Личные деньги (Общий баланс)</h3>
                        <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" id="use_global_balance" name="use_global_balance" value="1"
                                       data-balance="{{ $globalBalance }}" class="global-balance-checkbox w-5 h-5 accent-blue-600 rounded cursor-pointer">
                                <label for="use_global_balance" class="text-sm text-gray-700 cursor-pointer select-none">
                                    <span class="font-black text-gray-900 block">Оплатить с пополненного баланса</span>
                                    <span class="text-xs text-gray-400">Свободно на любые покупки: {{ number_format($globalBalance, 0, '.', ' ') }} ₸</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Правая колонка --}}
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Ваш заказ</h2>
                <div class="space-y-3 mb-6">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between border-b pb-2 text-gray-700">
                            <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                            <span>{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₸</span>
                        </div>
                    @endforeach
                </div>

                <div class="bg-gray-50 p-4 rounded-xl space-y-2 mb-6 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Сумма заказа:</span>
                        <span>{{ number_format($cart->total, 0, '.', ' ') }} ₸</span>
                    </div>

                    <div id="partnerDiscountRow" class="flex justify-between text-indigo-600 font-medium hidden">
                        <span>Скидки по купонам:</span>
                        <span>- <span id="partnerDiscountValue">0</span> ₸</span>
                    </div>

                    <div id="partnerCashbackRow" class="flex justify-between text-emerald-600 font-medium hidden">
                        <span>Списано бонусов магазинов:</span>
                        <span>- <span id="partnerCashbackValue">0</span> ₸</span>
                    </div>

                    <div id="globalBalanceRow" class="flex justify-between text-blue-600 font-medium hidden">
                        <span>Оплата с личного баланса:</span>
                        <span>- <span id="globalBalanceValue">0</span> ₸</span>
                    </div>

                    <div class="text-2xl font-bold pt-3 border-t text-gray-900 flex justify-between">
                        <span>Итого к оплате:</span>
                        <span><span id="displayFinalTotal">{{ number_format($finalTotal, 0, '.', ' ') }}</span> ₸</span>
                    </div>
                </div>

                <div id="cardDetailsContainer" class="p-6 border rounded-2xl bg-gray-50 transition-all duration-300">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Данные карты</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">Номер карты</label>
                            <input id="cardNumber" type="text" inputmode="numeric" placeholder="4242 4242 4242 4242" class="w-full border rounded-lg p-3 tracking-widest focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm text-gray-600">MM</label>
                                <input id="expMonth" type="text" inputmode="numeric" maxlength="2" placeholder="01" class="w-full border rounded-lg p-3 text-center focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">YY</label>
                                <input id="expYear" type="text" inputmode="numeric" maxlength="2" placeholder="26" class="w-full border rounded-lg p-3 text-center focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">CVV</label>
                                <input id="cvv" type="password" inputmode="numeric" maxlength="4" placeholder="777" class="w-full border rounded-lg p-3 text-center focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Имя держателя</label>
                            <input id="cardHolder" type="text" placeholder="IVAN IVANOV" class="w-full border rounded-lg p-3 uppercase focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <button type="submit" id="confirmOrder" class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl text-lg font-bold shadow-lg transition-all active:scale-95">
                    Оплатить <span id="buttonFinalTotal">{{ number_format($finalTotal, 0, '.', ' ') }}</span> ₸
                </button>
            </div>
        </form>
    </div>

    <script>
        const ttpId = "{{ $ttpPublicId }}";
        const checkout = new tiptop.Checkout({ publicId: ttpId });

        const baseFinalTotal = parseInt("{{ $finalTotal }}");
        const modal = document.getElementById('threeDsModal');
        const closeBtn = document.getElementById('close3ds');
        const confirmOrder = document.getElementById('confirmOrder');
        const cardInput = document.getElementById('cardNumber');
        const cardDetailsContainer = document.getElementById('cardDetailsContainer');

        const discountCbs = document.querySelectorAll('.partner-discount-checkbox');
        const cashbackCbs = document.querySelectorAll('.partner-cashback-checkbox');
        const globalBalanceCb = document.getElementById('use_global_balance');

        discountCbs.forEach(cb => cb.addEventListener('change', calculateTotals));
        cashbackCbs.forEach(cb => cb.addEventListener('change', calculateTotals));
        if (globalBalanceCb) globalBalanceCb.addEventListener('change', calculateTotals);

        function calculateTotals() {
            let currentRemainder = baseFinalTotal;

            // 1. Вычитаем купоны (если они есть)
            let couponsTotal = 0;
            if (typeof discountCbs !== 'undefined' && discountCbs.length > 0) {
                discountCbs.forEach(cb => { if (cb.checked) couponsTotal += parseInt(cb.dataset.amount); });
            }
            couponsTotal = Math.min(currentRemainder, couponsTotal);
            currentRemainder -= couponsTotal;

            // 2. Вычитаем целевые бонусы партнеров (если они есть)
            let cashbackTotal = 0;
            if (typeof cashbackCbs !== 'undefined' && cashbackCbs.length > 0) {
                cashbackCbs.forEach(cb => { if (cb.checked) cashbackTotal += parseInt(cb.dataset.amount); });
            }
            cashbackTotal = Math.min(currentRemainder, cashbackTotal);
            currentRemainder -= cashbackTotal;

            // 3. Вычитаем свободный баланс профиля
            let globalSpent = 0;
            if (globalBalanceCb && globalBalanceCb.checked) {
                globalSpent = Math.min(currentRemainder, parseInt(globalBalanceCb.dataset.balance));
                currentRemainder -= globalSpent;
            }

            // Обновляем отображение строк в чеке заказа
            updateRow('partnerDiscountRow', 'partnerDiscountValue', couponsTotal);
            updateRow('partnerCashbackRow', 'partnerCashbackValue', cashbackTotal);
            updateRow('globalBalanceRow', 'globalBalanceValue', globalSpent);

            // Обновляем итоговые цифры на странице
            const displayTotal = document.getElementById('displayFinalTotal');
            const buttonTotal = document.getElementById('buttonFinalTotal');

            if (displayTotal) displayTotal.innerText = currentRemainder.toLocaleString('ru-RU');

            // КРИТИЧЕСКОЕ ИСПРАВЛЕНИЕ: Управление активностью формы карты и текстом кнопки
            if (currentRemainder === 0) {
                // Если доплата картой не требуется (Итого = 0 ₸)
                if (cardDetailsContainer) {
                    cardDetailsContainer.style.opacity = '0.15';
                    cardDetailsContainer.style.pointerEvents = 'none';
                }
                confirmOrder.innerText = 'Оформить за счет бонусов';
            } else {
                // Если осталась сумма для оплаты картой (Итого > 0 ₸) — полностью включаем блок карты
                if (cardDetailsContainer) {
                    cardDetailsContainer.style.opacity = '1';
                    cardDetailsContainer.style.pointerEvents = 'auto';
                }
                // Возвращаем стандартный текст кнопки с динамической суммой
                confirmOrder.innerHTML = `Оплатить <span id="buttonFinalTotal">${currentRemainder.toLocaleString('ru-RU')}</span> ₸`;
            }
        }

        function updateRow(rowId, valId, amount) {
            const row = document.getElementById(rowId);
            const val = document.getElementById(valId);
            if (amount > 0) {
                if(val) val.innerText = amount.toLocaleString('ru-RU');
                if(row) row.classList.remove('hidden');
            } else {
                if(row) row.classList.add('hidden');
            }
        }

        cardInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 19) value = value.substring(0, 19);
            e.target.value = value.replace(/(.{4})/g, '$1 ').trim();
        });

        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            confirmOrder.disabled = true;
            confirmOrder.innerText = 'Обрабатываем платеж...';

            try {
                let couponsTotal = 0; discountCbs.forEach(cb => { if (cb.checked) couponsTotal += parseInt(cb.dataset.amount); });
                let cashbackTotal = 0; cashbackCbs.forEach(cb => { if (cb.checked) cashbackTotal += parseInt(cb.dataset.amount); });
                let globalSpent = (globalBalanceCb && globalBalanceCb.checked) ? Math.min((baseFinalTotal - couponsTotal - cashbackTotal), parseInt(globalBalanceCb.dataset.balance)) : 0;

                const finalPayAmount = baseFinalTotal - couponsTotal - cashbackTotal - globalSpent;
                let cryptogram = null;

                if (finalPayAmount > 0) {
                    const cardValue = cardInput.value.replace(/\s+/g, '');
                    const month = document.getElementById('expMonth').value;
                    const year = document.getElementById('expYear').value;
                    const cvv = document.getElementById('cvv').value;
                    const holder = document.getElementById('cardHolder').value || 'CARDHOLDER';

                    if (!cardValue || !month || !year || !cvv) throw new Error("Заполните карту.");
                    cryptogram = await checkout.createPaymentCryptogram({ cardNumber: cardValue, cvv: cvv, expDateMonth: month, expDateYear: year, cardHolderName: holder });
                }

                const fd = new FormData(this);
                let appliedDiscountIds = []; discountCbs.forEach(cb => { if (cb.checked) appliedDiscountIds.push(cb.value); });
                let usedPartnerCashbackIds = []; cashbackCbs.forEach(cb => { if (cb.checked) usedPartnerCashbackIds.push(cb.value); });

                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        name: fd.get('name'), phone: fd.get('phone'), address: fd.get('address'), cryptogram: cryptogram,
                        applied_discounts: appliedDiscountIds,
                        use_partner_cashbacks: usedPartnerCashbackIds,
                        use_global_balance: (globalBalanceCb && globalBalanceCb.checked) ? 1 : 0
                    })
                });

                const data = await response.json();
                if (data.status === '3ds_required') { show3DSForm(data.acs_url, data.pareq, data.transaction_id); return; }
                if (data.success || response.ok) { window.location.href = '/checkout/success'; } else { throw new Error(data.error); }

            } catch (err) {
                alert(err.message || 'Ошибка.');
                confirmOrder.disabled = false;
                calculateTotals();
            }
        });

        function show3DSForm(acsUrl, pareq, transactionId) {
            modal.style.display = 'flex';
            const form3ds = document.createElement('form'); form3ds.method = 'POST'; form3ds.action = acsUrl; form3ds.target = 'threeDsFrame';
            form3ds.innerHTML = `<input type="hidden" name="PaReq" value="${pareq}"><input type="hidden" name="MD" value="${transactionId}"><input type="hidden" name="TermUrl" value="{{ route('checkout.3ds.callback') }}">`;
            document.body.appendChild(form3ds); form3ds.submit(); form3ds.remove();
        }
    </script>
@endsection
