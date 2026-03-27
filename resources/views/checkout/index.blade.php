@extends('layouts.app')

@section('content')
    <script src="https://checkout.tiptoppay.kz/checkout.js"></script>
    <div class="max-w-4xl mx-auto px-4 py-10">

        <h1 class="text-3xl font-bold mb-8 text-gray-800">Оформление заказа</h1>

        {{-- Секция Розыгрыша Подарков --}}
        @if($gifts && $gifts->count() > 0)
            @foreach($gifts as $gift)
            <div class="mb-8 p-5 rounded-xl bg-gradient-to-r from-purple-50 to-indigo-50 border border-indigo-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-2xl">🎁</div>
                    <h2 class="text-xl font-semibold text-indigo-700">У вас есть шанс выиграть подарок!</h2>
                </div>
                <p class="text-gray-700 mb-4">После успешной оплаты будет проведён розыгрыш среди доступных подарков:</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-white border rounded-xl shadow-sm hover:shadow-md transition">
                        <div class="text-lg font-medium text-gray-900">{{ $gift['title'] }}</div>
                        <div class="text-gray-600 mt-1 text-sm">{{ $gift['description'] }}</div>
                        <div class="mt-3 flex items-center justify-between">
                            <div class="text-indigo-500 text-lg">⭐</div>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500">Подарок будет разыгран автоматически сразу после оплаты заказа.</p>
            </div>
            @endforeach
        @endif

        {{-- Секция Гарантированного Подарка по Промокоду --}}
        @if($activePromo && $activePromo->share && $activePromo->share->promo === 'gift')
            <div class="mb-8 p-5 rounded-xl bg-blue-50 border border-blue-200 shadow-sm flex items-center gap-4">
                <div class="text-3xl">🎉</div>
                <div>
                    <h3 class="font-bold text-blue-800 text-lg">Ваш промокод активирован!</h3>
                    <p class="text-blue-700">К вашему заказу будет добавлен гарантированный подарок после оплаты.</p>
                </div>
            </div>
        @endif

        {{-- Модальное окно 3DS --}}
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
                              class="w-full border rounded-lg p-3 mt-1 focus:ring-indigo-500 focus:border-indigo-500">{{ auth()->user()->address }}</textarea>
                </label>
            </div>

            {{-- Правая колонка --}}
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Ваш заказ</h2>
                <div class="space-y-3 mb-6">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between border-b pb-2 text-gray-700">
                            <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                            <span>{{ number_format($item->total, 0, '.', ' ') }} ₸</span>
                        </div>
                    @endforeach
                </div>

                {{-- Расчет итогов со скидкой --}}
                <div class="bg-gray-50 p-4 rounded-xl space-y-2 mb-6 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Сумма заказа:</span>
                        <span>{{ number_format($cart->total, 0, '.', ' ') }} ₸</span>
                    </div>

                    @if($discount > 0)
                        <div class="flex justify-between text-green-600 font-medium">
                            <span>Скидка ({{ $activePromo->share->size }}%):</span>
                            <span>- {{ number_format($discount, 0, '.', ' ') }} ₸</span>
                        </div>
                    @endif

                    <div class="text-2xl font-bold pt-3 border-t text-gray-900 flex justify-between">
                        <span>Итого:</span>
                        <span>{{ number_format($finalTotal, 0, '.', ' ') }} ₸</span>
                    </div>
                </div>

                {{-- Данные карты --}}
                <div class="p-6 border rounded-2xl bg-gray-50">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Данные карты</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm text-gray-600">Номер карты</label>
                            <input id="cardNumber" type="text" inputmode="numeric" placeholder="4242 4242 4242 4242"
                                   class="w-full border rounded-lg p-3 tracking-widest focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm text-gray-600">MM</label>
                                <input id="expMonth" type="text" inputmode="numeric" maxlength="2" placeholder="01"
                                       class="w-full border rounded-lg p-3 text-center focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">YY</label>
                                <input id="expYear" type="text" inputmode="numeric" maxlength="2" placeholder="26"
                                       class="w-full border rounded-lg p-3 text-center focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="text-sm text-gray-600">CVV</label>
                                <input id="cvv" type="password" inputmode="numeric" maxlength="4" placeholder="777"
                                       class="w-full border rounded-lg p-3 text-center focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Имя держателя</label>
                            <input id="cardHolder" type="text" placeholder="IVAN IVANOV"
                                   class="w-full border rounded-lg p-3 uppercase focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <button type="submit" id="confirmOrder"
                        class="mt-6 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-xl text-lg font-bold shadow-lg transition-all active:scale-95">
                    Оплатить {{ number_format($finalTotal, 0, '.', ' ') }} ₸
                </button>
            </div>
        </form>
    </div>

    <script>
        // 1. Инициализация (убедись, что ttpPublicId не пустой в исходном коде страницы)
        const ttpId = "{{ $ttpPublicId }}";
        console.log("TipTopPay Public ID:", ttpId);

        const checkout = new tiptop.Checkout({ publicId: ttpId });

        const modal = document.getElementById('threeDsModal');
        const closeBtn = document.getElementById('close3ds');
        const confirmOrder = document.getElementById('confirmOrder');
        const cardInput = document.getElementById('cardNumber');

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
            document.getElementById('threeDsFrame').src = '';
        });

        cardInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 19) value = value.substring(0, 19);
            e.target.value = value.replace(/(.{4})/g, '$1 ').trim();
        });

        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Блокируем кнопку СРАЗУ
            confirmOrder.disabled = true;
            confirmOrder.innerText = 'Обрабатываем платеж...';
            console.log("Начинаем процесс оплаты...");

            // Небольшая пауза 100мс, чтобы браузер успел отрисовать новый текст кнопки
            await new Promise(resolve => setTimeout(resolve, 100));

            try {
                const cardValue = cardInput.value.replace(/\s+/g, '');
                const month = document.getElementById('expMonth').value;
                const year = document.getElementById('expYear').value;
                const cvv = document.getElementById('cvv').value;
                const holder = document.getElementById('cardHolder').value || 'CARDHOLDER';

                console.log("Данные карты собраны, создаем криптограмму...");

                // 2. Создаем криптограмму
                const cryptogram = await checkout.createPaymentCryptogram({
                    cardNumber: cardValue,
                    cvv: cvv,
                    expDateMonth: month,
                    expDateYear: year,
                    cardHolderName: holder,
                });

                console.log("Результат криптограммы:", cryptogram);

                if (!cryptogram) {
                    throw new Error("Библиотека не смогла создать криптограмму. Проверьте правильность номера карты и даты.");
                }

                // 3. Отправка на сервер
                // Используем FormData для надежности получения полей
                const fd = new FormData(this);

                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: fd.get('name'),
                        phone: fd.get('phone'),
                        address: fd.get('address'),
                        cryptogram: cryptogram
                    })
                });

                const data = await response.json();
                console.log("Ответ сервера:", data);

                if (data.status === '3ds_required') {
                    show3DSForm(data.acs_url, data.pareq, data.transaction_id);
                    return;
                }

                if (data.success || response.ok) {
                    window.location.href = '/checkout/success';
                } else {
                    throw new Error(data.error || 'Ошибка сервера при оплате');
                }

            } catch (err) {
                console.error('Критическая ошибка:', err);
                alert(err.message || 'Произошла ошибка при оплате.');

                confirmOrder.disabled = false;
                confirmOrder.innerText = 'Оплатить {{ number_format($finalTotal, 0, '.', ' ') }} ₸';
            }
        });

        function show3DSForm(acsUrl, pareq, transactionId) {
            modal.style.display = 'flex';
            const form3ds = document.createElement('form');
            form3ds.method = 'POST';
            form3ds.action = acsUrl;
            form3ds.target = 'threeDsFrame';
            form3ds.innerHTML = `
            <input type="hidden" name="PaReq" value="${pareq}">
            <input type="hidden" name="MD" value="${transactionId}">
            <input type="hidden" name="TermUrl" value="{{ route('checkout.3ds.callback') }}">
        `;
            document.body.appendChild(form3ds);
            form3ds.submit();
            form3ds.remove();
        }
    </script>
@endsection
