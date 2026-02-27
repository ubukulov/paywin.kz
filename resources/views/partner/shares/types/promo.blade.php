<form action="{{ route('partner.my-shares.store') }}"
      method="post"
      x-data="{ bonusType: 'discount' }"
      class="max-w-2xl mx-auto rounded-2xl space-y-6 bg-white p-1 shadow-sm">
    @csrf

    <input type="hidden" name="type" value="promocode">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Название промокода</label>
        <input name="title" required placeholder="ВВЕДИТЕ НАЗВАНИЕ" style="text-transform: uppercase"
               class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition" />
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-3">Тип бонуса</label>
        <div class="flex flex-wrap gap-2 p-1 bg-gray-100 rounded-xl w-fit">
            <label class="cursor-pointer">
                <input type="radio" name="bonus_type" value="discount" x-model="bonusType" class="sr-only peer">
                <div class="px-5 py-2 rounded-lg transition peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-indigo-600 text-gray-500 text-sm font-medium">Скидка</div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="bonus_type" value="money" x-model="bonusType" class="sr-only peer">
                <div class="px-5 py-2 rounded-lg transition peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-indigo-600 text-gray-500 text-sm font-medium">Деньги</div>
            </label>
            <label class="cursor-pointer">
                <input type="radio" name="bonus_type" value="gift" x-model="bonusType" class="sr-only peer">
                <div class="px-5 py-2 rounded-lg transition peer-checked:bg-white peer-checked:shadow-sm peer-checked:text-indigo-600 text-gray-500 text-sm font-medium">Подарок</div>
            </label>
        </div>
    </div>

    <div class="bg-gray-50 p-6 rounded-xl border border-dashed border-gray-300 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div x-show="bonusType !== 'gift'">
                <label class="block text-sm font-medium text-gray-700 mb-1"
                       x-text="bonusType === 'discount' ? 'Размер скидки (%)' : 'Сумма к зачислению (₸)'"></label>
                <input type="number" min="1" name="size" :required="bonusType !== 'gift'" placeholder="Напр: 500"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-200 outline-none">
            </div>

            <div x-show="bonusType === 'gift'" class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Что получит пользователь?</label>
                <input type="text" name="gift_description" :required="bonusType === 'gift'" placeholder="Напр: Бесплатный бургер"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-200 outline-none">
            </div>

            <div x-show="bonusType === 'discount'" x-transition>
                <label class="block text-sm font-medium text-gray-700 mb-1">Мин. сумма заказа (₸)</label>
                <input type="number" min="0" name="min_sum" placeholder="Напр: 5000"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-200 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Лимит активаций (всего)</label>
                <input type="number" min="1" name="usage_limit" required placeholder="Напр: 100"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-indigo-200 outline-none">
            </div>

            <div class="col-span-full p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                <label class="block text-sm font-bold text-indigo-900 mb-2">💸 Вознаграждение агента</label>
                <div class="flex items-center gap-4">
                    <div class="relative w-32">
                        <input type="number" name="agent_percent" min="0" max="100" step="0.1" required
                               class="w-full rounded-lg border border-indigo-300 pl-4 pr-8 py-2 outline-none focus:ring-2 focus:ring-indigo-200" placeholder="0">
                        <span class="absolute right-3 top-2 text-gray-400">%</span>
                    </div>
                    <p class="text-xs text-indigo-600 leading-tight">
                        Укажите процент, который получит агент от суммы каждой <br> покупки привлеченного им клиента.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1 text-xs uppercase font-bold">Дата начала</label>
            <input type="datetime-local" name="from_date" required class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1 text-xs uppercase font-bold">Дата окончания</label>
            <input type="datetime-local" name="to_date" required class="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none">
        </div>
    </div>

    <div class="flex items-center justify-between pt-4 border-t">
        <a href="{{ route('partner.my-shares.index') }}" class="flex items-center text-gray-500 hover:text-indigo-600 transition">
            <img src="/images/cabinet/left-arrow.svg" class="mr-2" alt="icon"> вернуться
        </a>
        <button type="submit" class="bg-indigo-600 text-white px-8 py-2.5 rounded-lg hover:bg-indigo-700 transition font-medium shadow-md">
            Создать промокод
        </button>
    </div>
</form>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
