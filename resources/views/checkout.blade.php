@extends('layouts.app')

@section('content')
    <script src="https://checkout.tiptoppay.kz/checkout.js"></script>
    <div class="max-w-4xl mx-auto px-4 py-10">

        <h1 class="text-3xl font-bold mb-8 text-gray-800">Оформление заказа</h1>

        {{-- Подарки — показываем перед формой --}}
        @if($gift)
            <div class="mb-8 p-5 rounded-xl bg-gradient-to-r from-purple-50 to-indigo-50 border border-indigo-200 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-2xl">
                        🎁
                    </div>
                    <h2 class="text-xl font-semibold text-indigo-700">У вас есть шанс выиграть подарок!</h2>
                </div>

                <p class="text-gray-700 mb-4">После успешной оплаты будет проведён розыгрыш среди доступных подарков:</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-white border rounded-xl shadow-sm hover:shadow-md transition">
                        <div class="text-lg font-medium text-gray-900">{{ $gift['title'] }}</div>
                        <div class="text-gray-600 mt-1 text-sm">{{ $gift['description'] }}</div>

                        <div class="mt-3 flex items-center justify-between">
                            <div class="text-sm text-indigo-600 font-semibold">
                                Шанс: {{ $gift['chance'] }}%
                            </div>
                            <div class="text-indigo-500 text-lg">⭐</div>
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-sm text-gray-500">
                    Подарок будет разыгран автоматически сразу после оплаты заказа.
                </p>
            </div>
        @endif


        <form action="{{ route('checkout.store') }}" method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
            @csrf

            {{-- Левая колонка --}}
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Данные покупателя</h2>

                <label class="block mb-4">
                    <span class="text-sm font-medium text-gray-700">Имя</span>
                    <input type="text" name="name" required
                           class="w-full border rounded-lg p-3 mt-1 focus:ring-indigo-500 focus:border-indigo-500">
                </label>

                <label class="block mb-4">
                    <span class="text-sm font-medium text-gray-700">Телефон</span>
                    <input type="text" name="phone" required
                           class="w-full border rounded-lg p-3 mt-1 focus:ring-indigo-500 focus:border-indigo-500">
                </label>

                <label class="block mb-4">
                    <span class="text-sm font-medium text-gray-700">Адрес доставки</span>
                    <textarea name="address" rows="3" required
                              class="w-full border rounded-lg p-3 mt-1 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </label>
            </div>

            {{-- Правая колонка --}}
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Ваш заказ</h2>

                <div class="space-y-3">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between border-b pb-2 text-gray-700">
                            <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                            <span>{{ number_format($item->total, 0, '.', ' ') }} ₸</span>
                        </div>
                    @endforeach
                </div>

                <div class="text-2xl font-semibold mt-6 text-gray-900">
                    Итого: {{ number_format($cart->total, 0, '.', ' ') }} ₸
                </div>

                {{-- Card data --}}
                <div class="mt-8 p-6 border rounded-2xl bg-gray-50">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Данные карты</h3>

                    <div class="space-y-4">

                        <div>
                            <label class="text-sm text-gray-600">Номер карты</label>
                            <input
                                id="cardNumber"
                                type="text"
                                inputmode="numeric"
                                autocomplete="cc-number"
                                placeholder="4242 4242 4242 4242"
                                maxlength="23"
                                class="w-full border rounded-lg p-3 tracking-widest
                       focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="text-sm text-gray-600">MM</label>
                                <input
                                    id="expMonth"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="2"
                                    placeholder="01"
                                    class="w-full border rounded-lg p-3 text-center
                           focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">YY</label>
                                <input
                                    id="expYear"
                                    type="text"
                                    inputmode="numeric"
                                    maxlength="2"
                                    placeholder="26"
                                    class="w-full border rounded-lg p-3 text-center
                           focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label class="text-sm text-gray-600">CVV</label>
                                <input
                                    id="cvv"
                                    type="password"
                                    inputmode="numeric"
                                    maxlength="4"
                                    placeholder="777"
                                    class="w-full border rounded-lg p-3 text-center
                           focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="text-sm text-gray-600">Имя держателя</label>
                            <input
                                id="cardHolder"
                                type="text"
                                autocomplete="cc-name"
                                placeholder="CHAD VIRGIN"
                                class="w-full border rounded-lg p-3 uppercase
                       focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <button
                            type="button"
                            id="generateCryptogram"
                            class="w-full bg-blue-600 hover:bg-blue-700
                   text-white py-3 rounded-lg font-medium transition">
                            Создать криптограмму
                        </button>

                        <p id="cryptoStatus" class="text-sm text-center"></p>
                    </div>
                </div>

                {{-- Единственное, что уходит на сервер --}}
                <input type="hidden" name="cryptogram" id="cryptogram">

                <button id="confirmOrder" disabled class="mt-6 w-full bg-gray-400 text-white py-3 rounded-lg text-lg font-medium shadow cursor-not-allowed">
                    Подтвердить заказ
                </button>
            </div>
        </form>
    </div>

    <script>
        const checkout = new tiptop.Checkout({
            publicId: "{{ $ttpPublicId }}",
        });

        const cardInput = document.getElementById('cardNumber');
        const confirmOrder = document.getElementById('confirmOrder');

        // Маска + ограничение 16–19 цифр
        cardInput.addEventListener('input', () => {
            let digits = cardInput.value.replace(/\D/g, '');

            if (digits.length > 19) {
                digits = digits.substring(0, 19);
            }

            cardInput.value = digits.replace(/(.{4})/g, '$1 ').trim();
        });

        document.getElementById('generateCryptogram').addEventListener('click', () => {

            const cardDigits = cardInput.value.replace(/\D/g, '');

            if (cardDigits.length < 16 || cardDigits.length > 19) {
                showError('Номер карты должен содержать от 16 до 19 цифр');
                return;
            }

            const fieldValues = {
                cardNumber: cardDigits,
                cvv: document.getElementById('cvv').value,
                expDateMonth: document.getElementById('expMonth').value,
                expDateYear: document.getElementById('expYear').value,
                cardHolderName: document.getElementById('cardHolder').value,
            };

            checkout.createPaymentCryptogram(fieldValues)
                .then(cryptogram => {
                    document.getElementById('cryptogram').value = cryptogram;
                    confirmOrder.disabled = false;
                    confirmOrder.className = "mt-6 w-full bg-indigo-600 hover:bg-indigo-700 transition text-white py-3 rounded-lg text-lg font-medium shadow";
                    confirmOrder.classList.remove('cursor-not-allowed');
                    showSuccess('Криптограмма успешно создана ✅');
                })
                .catch(errors => {
                    showError(errors.join(', '));
                });
        });

        function showError(text) {
            const el = document.getElementById('cryptoStatus');
            el.innerText = text;
            el.className = 'text-sm text-center text-red-600';
        }

        function showSuccess(text) {
            const el = document.getElementById('cryptoStatus');
            el.innerText = text;
            el.className = 'text-sm text-center text-green-600';
        }
    </script>
@endsection
