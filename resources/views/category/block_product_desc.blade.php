{{-- ИНФОРМАЦИОННЫЙ БЛОК: ТАБЫ (Описание, Характеристики, Условия) --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden mt-4">
    {{-- Навигация табов --}}
    <div class="flex border-b border-gray-100 bg-gray-50/50">
        <button onclick="switchTab('description')" id="tab-btn-description"
                class="tab-btn flex-1 sm:flex-none text-center px-6 py-4 text-sm font-black text-indigo-600 border-b-2 border-indigo-600 transition duration-200 outline-none">
            Описание
        </button>
        <button onclick="switchTab('features')" id="tab-btn-features"
                class="tab-btn flex-1 sm:flex-none text-center px-6 py-4 text-sm font-bold text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-200 transition duration-200 outline-none">
            Характеристики
        </button>
        <button onclick="switchTab('conditions')" id="tab-btn-conditions"
                class="tab-btn flex-1 sm:flex-none text-center px-6 py-4 text-sm font-bold text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-200 transition duration-200 outline-none">
            Условия акции
        </button>
    </div>

    {{-- Содержимое табов --}}
    <div class="p-6">
        {{-- Вкладка 1: Описание --}}
        <div id="tab-content-description" class="tab-content prose max-w-none text-sm text-gray-700 leading-relaxed">
            {!! $product->description !!}
        </div>

        {{-- Вкладка 2: Характеристики --}}
        <div id="tab-content-features" class="tab-content hidden">
            @if($product->data && (is_array($product->data) ? !empty($product->data) : trim($product->data) !== ''))
                <div class="max-w-2xl">
                    <table class="w-full text-sm text-left text-gray-600">
                        <tbody>
                        @if(is_array($product->data))
                            @foreach($product->data as $key => $value)
                                <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                                    <td class="py-3 pr-4 font-bold text-gray-400 w-1/3">{{ $key }}</td>
                                    <td class="py-3 text-gray-800 font-medium">{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="border-b border-gray-50">
                                <td class="py-3 text-gray-800 whitespace-pre-wrap">{{ $product->data }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-6 text-sm text-gray-400 font-medium">
                    Производитель не указал детальные характеристики для этого товара.
                </div>
            @endif
        </div>

        {{-- Вкладка 3: Условия акции --}}
        <div id="tab-content-conditions" class="tab-content hidden">
            <div class="max-w-3xl space-y-4">
                <div class="p-4 bg-orange-50/40 border border-orange-100 rounded-2xl flex gap-3.5 items-start">
                    <span class="text-lg mt-0.5">🎁</span>
                    <div class="text-sm">
                        <h4 class="font-black text-orange-900 mb-1">Как получить Рандомный бонус от партнера?</h4>
                        <p class="text-gray-600 leading-relaxed">
                            Добавьте товар в корзину и оформите заказ. При общей сумме покупки в чеке выше
                            <span class="font-black text-orange-600">{{ number_format($gifts->first()->from_order ?? 4000, 0, '.', ' ') }} ₸</span>,
                            система автоматически выберет один из доступных подарков партнера. Вы увидите выигранный купон сразу на странице завершения оплаты.
                        </p>
                    </div>
                </div>

                @if(isset($platformPromotions) && $platformPromotions->isNotEmpty())
                    @foreach($platformPromotions as $promo)
                        <div class="p-4 bg-indigo-50/40 border border-indigo-100 rounded-2xl flex gap-3.5 items-start">
                            <span class="text-lg mt-0.5">⚡</span>
                            <div class="text-sm">
                                <h4 class="font-black text-indigo-900 mb-1">Глобальная акция: {{ $promo->title }}</h4>
                                <p class="text-gray-600 leading-relaxed">
                                    Данный товар принимает участие в официальной акции от платформы **Paywin.kz**.
                                    @if($promo->reward_type === 'raffle')
                                        За покупку начисляется сертифицированный <span class="font-bold text-indigo-600 underline">Билет участника</span> для розыгрыша суперпризов.
                                    @else
                                        Вы гарантированно получаете один из подарков фонда платформы.
                                    @endif
                                    @if($promo->end_at)
                                        Успейте принять участие до <span class="font-bold text-gray-800">{{ $promo->end_at->format('d.m.Y') }}</span>.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif

                <div class="p-4 bg-gray-50 border border-gray-100 rounded-2xl flex gap-3.5 items-start">
                    <span class="text-lg mt-0.5">📌</span>
                    <div class="text-sm text-gray-500 leading-relaxed">
                        <h4 class="font-bold text-gray-700 mb-1">Общие правила выдачи призов</h4>
                        <p>Все выигранные бонусы, промокоды и билеты мгновенно привязываются к вашему профилю и доступны в личном кабинете в разделе «Мои призы». Призы не подлежат обмену на денежный эквивалент. При возврате товара начисленные за него бонусы и билеты аннулируются.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // 1. Скрываем весь контент вкладок
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        // 2. Сбрасываем стили со всех кнопок управления
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('text-indigo-600', 'border-indigo-600', 'font-black');
            btn.classList.add('text-gray-500', 'border-transparent', 'font-bold');
        });

        // 3. Показываем контент выбранной вкладки
        const targetContent = document.getElementById(`tab-content-${tabName}`);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }

        // 4. Подсвечиваем активную кнопку
        const targetBtn = document.getElementById(`tab-btn-${tabName}`);
        if (targetBtn) {
            targetBtn.classList.remove('text-gray-500', 'border-transparent', 'font-bold');
            targetBtn.classList.add('text-indigo-600', 'border-indigo-600', 'font-black');
        }
    }
</script>
