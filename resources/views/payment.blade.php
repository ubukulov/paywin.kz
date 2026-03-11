@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-6 pb-32">
        {{-- Заголовок --}}
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 animate__animated animate__fadeInLeft text-center sm:text-left">Оплата</h1>

        @php $partnerProfile = $partner->partnerProfile; @endphp

        {{-- Профиль партнера --}}
        <div class="flex items-center gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8 animate__animated animate__fadeIn">
            <div class="w-16 h-16 flex-shrink-0 rounded-xl overflow-hidden border border-gray-100">
                <img src="{{ empty($partnerProfile->logo) ? '/images/partner-description/partner-logo.png' : $partnerProfile->logo }}"
                     alt="logo" class="w-full h-full object-contain">
            </div>
            <div>
                <div class="text-lg font-bold text-gray-900 leading-tight">{{ $partnerProfile->company }}</div>
                <div class="text-sm text-gray-500">{{ $partnerProfile->category->title }}</div>
            </div>
        </div>

        @csrf
        <input type="hidden" id="partner_id" name="partner_id" value="{{ $id }}">

        <div class="space-y-6">
            {{-- Ввод суммы --}}
            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-200 text-center">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 animate__animated animate__fadeIn">Сумма оплаты</h2>
                <div class="relative inline-block w-full max-w-[240px]">
                    <input id="payment_input"
                           type="number"
                           inputmode="numeric"
                           required
                           name="sum"
                           placeholder="0"
                           class="w-full text-3xl font-bold text-center bg-transparent border-b-2 border-gray-300 focus:border-[#18BE1E] focus:outline-none pb-2 transition-colors">
                    <span class="absolute right-4 bottom-2 text-xl font-bold text-gray-400">₸</span>
                </div>
            </div>

            {{-- Карты --}}
            {{--@if($cards = $user->getMyCards())
                <div class="action bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase mb-3 text-nowrap">Метод оплаты</h3>
                    @include('_partials._payment_cards', $cards)
                </div>
            @endif--}}

            {{-- Баланс --}}
            @if($user_balance = $user->getBalanceAttribute())
                <div class="action bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase mb-3">Потратить баланс</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center">
                                <img src="/b5/img/icons/wallet.svg" alt="кошёлек" class="w-5 h-5">
                            </div>
                            <p id="balance" class="text-lg font-bold text-gray-800">{{ $user_balance }} ₸</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input scale-125 cursor-pointer accent-[#18BE1E]" type="checkbox" id="flexSwitchCheckDefault">
                        </div>
                    </div>
                    <input type="hidden" id="balance_hidden" name="balance" value="0">
                </div>
            @endif

            {{-- Скидка --}}
            @if($user_discount = $user->getDiscountForUser())
                <div class="action bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase mb-3">Применить скидку</h3>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center">
                                <img src="/b5/img/icons/precent.svg" alt="проценты" class="w-5 h-5">
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-800"><span id="discount">{{ $user_discount->size }}%</span></p>
                                <span id="discount_max_sum" data-sum="{{ $user_discount->max_sum }}" class="text-[10px] text-gray-400">до {{ number_format($user_discount->max_sum, 0, '.', ' ') }} тг</span>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input scale-125 cursor-pointer accent-[#18BE1E]" type="checkbox" id="discount_input">
                        </div>
                    </div>
                    <input type="hidden" id="discount_hidden" name="discount" value="0">
                </div>
            @endif

            <hr class="border-gray-100 my-8">

            {{-- Итоговый блок (Чек) --}}
            <div id="to_payment" class="hidden animate__animated animate__fadeIn space-y-3 bg-gray-900 text-white rounded-3xl p-6 shadow-2xl my-6">
                <h3 class="text-xs font-bold uppercase text-gray-400 tracking-widest mb-4">Детали платежа</h3>

                <div class="flex justify-between items-center text-sm border-b border-gray-800 pb-2">
                    <span class="text-gray-400">Сумма заказа:</span>
                    <span id="base_sum_val" class="font-bold text-white">0 ₸</span>
                </div>

                <div id="discount_row" class="hidden flex justify-between items-center text-sm border-b border-gray-800 pb-2">
                    <span class="text-gray-400">Скидка:</span>
                    <span id="discount_size_val" class="font-bold text-[#18BE1E]">-0 ₸</span>
                </div>

                <div id="balance_row" class="hidden flex justify-between items-center text-sm border-b border-gray-800 pb-2">
                    <span class="text-gray-400">С баланса:</span>
                    <span id="balance_size_val" class="font-bold text-red-400">-0 ₸</span>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <span class="text-lg font-bold">Итого к оплате:</span>
                    <div class="text-right">
                        <p id="final_sum_display" class="text-3xl font-black text-[#18BE1E]">0 ₸</p>
                        <input type="hidden" id="sum_amount_hidden" name="sum_amount" value="0">
                    </div>
                </div>
            </div>
        </div>

        {{-- Слайдер призов --}}
        <div class="mt-12 mb-8 animate__animated animate__fadeIn">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Выиграйте один из призов</h2>
            <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
                @foreach($partner->sharesWithoutPromocodes as $share)
                    <div class="flex-shrink-0 bg-white border border-gray-100 p-4 rounded-2xl shadow-sm min-w-[180px]">
                        <p class="text-sm font-bold text-gray-800 leading-snug">
                            {{ \Illuminate\Support\Str::limit($share->title) }}<br>
                            @if($share->type != 'promocode')
                            <span class="text-xs font-medium text-[#18BE1E]">от {{ number_format($share->data['from_order'], 0, '.', ' ') }} ₸</span>
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Футер с кнопками --}}
        <div class="fixed bottom-0 left-0 right-0 p-6 bg-white border-t border-gray-100 z-50 flex items-center justify-center max-w-xl mx-auto shadow-[0_-10px_30px_rgba(0,0,0,0.05)]">
            <div class="flex items-center gap-4 w-full">
                <a href="{{ route('showPartner', ['slug' => $slug, 'id' => $id]) }}"
                   class="flex-1 text-center py-4 bg-gray-50 text-gray-400 font-bold rounded-3xl hover:bg-gray-100 transition-colors uppercase tracking-wider text-sm">
                    вернуться
                </a>
                <button type="button" id="paymentBtn"
                        class="flex-[2] py-4 bg-[#18BE1E] text-white font-black rounded-3xl shadow-[0_10px_20px_rgba(24,190,30,0.4)] hover:scale-[1.02] transition-transform active:scale-95 uppercase tracking-wider text-lg">
                    оплатить
                </button>
            </div>
        </div>
    </div>

    {{-- Загрузчик --}}
    <div id="paymentLoader" class="fixed inset-0 bg-white/95 hidden items-center justify-center z-[99999] backdrop-blur-sm">
        <div class="text-center p-10 bg-white rounded-3xl shadow-2xl border border-gray-100">
            <div class="w-24 h-24 rounded-full border-4 border-gray-100 border-t-[#18BE1E] flex items-center justify-center mx-auto mb-6 animate-spin">
                <span class="text-3xl font-black text-[#18BE1E] animate-pulse">₸</span>
            </div>
            <p class="text-xl font-bold text-gray-900 mb-2">Обрабатываем платеж</p>
            <p class="text-sm text-gray-400">Пожалуйста, не закрывайте страницу</p>
        </div>
    </div>

    <script src="https://widget.tiptoppay.kz/bundles/widget.js"></script>

    <script>
        $(document).ready(function() {
            // 1. Инициализация элементов
            const $input = $('#payment_input');
            const $block = $('#to_payment');
            const $balanceSwitch = $('#flexSwitchCheckDefault');
            const $discountSwitch = $('#discount_input');

            const $baseDisplay = $('#base_sum_val');
            const $discountDisplay = $('#discount_size_val');
            const $balanceDisplay = $('#balance_size_val');
            const $finalDisplay = $('#final_sum_display');
            const $finalHiddenInput = $('#sum_amount_hidden');

            const $discountRow = $('#discount_row');
            const $balanceRow = $('#balance_row');
            const $paymentBtn = $('#paymentBtn');
            const $loader = $('#paymentLoader');

            // Данные пользователя
            const userBalance = parseInt($('#balance').text()) || 0;
            const discountPercent = parseInt($('#discount').text()) || 0;
            const discountMax = parseInt($('#discount_max_sum').data('sum')) || 0;

            // === Функция пересчета ===
            function updateAll() {
                let amount = parseInt($input.val()) || 0;

                if (amount <= 0) {
                    $block.addClass('hidden').hide();
                    $finalHiddenInput.val(0);
                    return;
                }

                $block.removeClass('hidden').show();
                $baseDisplay.text(amount + " ₸");

                let currentTotal = amount;

                // Расчет скидки
                if ($discountSwitch.prop('checked')) {
                    let calcDiscount = Math.floor((amount * discountPercent) / 100);
                    let finalDiscount = Math.min(calcDiscount, discountMax);
                    currentTotal -= finalDiscount;
                    $discountDisplay.text("-" + finalDiscount + " ₸");
                    $discountRow.removeClass('hidden');
                    $('#discount_hidden').val(1);
                } else {
                    $discountRow.addClass('hidden');
                    $('#discount_hidden').val(0);
                }

                // Расчет баланса
                if ($balanceSwitch.prop('checked')) {
                    let balanceToUse = Math.min(currentTotal, userBalance);
                    currentTotal -= balanceToUse;
                    $balanceDisplay.text("-" + balanceToUse + " ₸");
                    $balanceRow.removeClass('hidden');
                    $('#balance_hidden').val(1);
                } else {
                    $balanceRow.addClass('hidden');
                    $('#balance_hidden').val(0);
                }

                // Итоговые значения
                $finalDisplay.text(currentTotal + " ₸");
                $finalHiddenInput.val(currentTotal);
            }

            // Слушатели ввода
            $input.on('input', updateAll);
            $balanceSwitch.on('change', updateAll);
            $discountSwitch.on('change', updateAll);

            // === Логика оплаты ===
            $paymentBtn.on('click', function(e) {
                e.preventDefault();

                // Принудительно обновляем расчеты перед оплатой
                updateAll();

                let amountSum = Number($finalHiddenInput.val());
                let amountToPay = Number($finalHiddenInput.val());
                let totalOrderSum = Number($input.val());
                let balanceUsed = $('#balance_hidden').val() == 1 ? Math.min(totalOrderSum, userBalance) : 0;

                // Если в итоговом поле 0, пробуем взять из основного ввода (на случай сбоя расчета)
                if (!amountSum || amountSum <= 0) {
                    amountSum = Number($input.val());
                }

                const partnerId = $('#partner_id').val();
                const csrf = $('input[name="_token"]').val();

                if (!amountSum || isNaN(amountSum) || amountSum <= 0) {
                    alert('Пожалуйста, введите корректную сумму оплаты');
                    $input.focus();
                    return;
                }

                if (typeof tiptop === 'undefined') {
                    alert('Ошибка: Платежный модуль не загружен. Обновите страницу.');
                    return;
                }

                // Визуальная блокировка кнопки
                $paymentBtn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');

                // === ПУТЬ 1: Полная оплата с баланса (Банк не нужен) ===
                if (amountToPay <= 0 && balanceUsed > 0) {
                    $loader.css('display', 'flex').show();

                    fetch('/payment-with-balance', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({
                            transaction_id: 'INTERNAL_BALANCE', // Пометка для сервера
                            amount: totalOrderSum,
                            balance_used: balanceUsed,
                            partner_id: partnerId
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if(data.status === 'success') {
                                if (data.prize) sessionStorage.setItem('last_prize', JSON.stringify(data.prize));
                                window.location.href = '/success/payment';
                            } else {
                                throw new Error("Ошибка при оплате. Попробуйте позже");
                            }
                        })
                        .catch(() => {
                            $loader.hide();
                            alert('Ошибка при списании с баланса');
                            $paymentBtn.prop('disabled', false).removeClass('opacity-50');
                        });

                    return; // Выходим, чтобы не запускать виджет
                }

                const widget = new tiptop.Widget();



                const intentParams = {
                    publicTerminalId: "pk_ee882e56bdffee4bea6f8f97290c6",
                    paymentSchema: 'Dual',
                    currency: "KZT",
                    amount: amountSum,
                    successRedirectUrl: "https://paywin.kz/success/payment",
                    failRedirectUrl: "https://paywin.kz/error/payment",
                    tokenize: true,
                };

                widget.start(intentParams)
                    .then(result => {
                        if (result?.data?.transactionId) {
                            $loader.css('display', 'flex').show();

                            fetch('/payment', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: JSON.stringify({
                                    transaction_id: result.data.transactionId,
                                    amount: amountSum,
                                    partner_id: partnerId
                                })
                            })
                                .then(() => {
                                    window.location.href = '/success/payment';
                                })
                                .catch(() => {
                                    $loader.hide();
                                    window.location.href = '/error/payment';
                                });
                        } else {
                            $paymentBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                        }
                    })
                    .catch(error => {
                        console.error("Payment error:", error);
                        $paymentBtn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                    });
            });

            // Первичный запуск
            updateAll();
        });
    </script>
@stop
