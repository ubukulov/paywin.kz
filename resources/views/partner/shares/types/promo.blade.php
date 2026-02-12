<form action="{{ route('partner.my-shares.store') }}"
      method="post"
      class="max-w-2xl mx-auto rounded-2xl space-y-6">
    @csrf

    <input type="hidden" name="type" value="promocode">

    <!-- Название подарка -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Название промокода
        </label>
        <input
            name="title"
            required
            placeholder="Введите название"
            style="text-transform: uppercase"
            class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
        />
    </div>

    <!-- Скидка / Деньги -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Тип бонуса
        </label>

        <div class="flex items-center gap-6">
            <label class="relative inline-flex items-center cursor-pointer" style="font-size: 14px;">Скидка</label>

            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="discount_or_money" class="sr-only peer">
                <div
                    class="w-11 h-6 bg-gray-300 rounded-full peer
                               peer-checked:bg-indigo-600
                               after:content-[''] after:absolute after:top-0.5 after:left-[2px]
                               after:bg-white after:rounded-full after:h-5 after:w-5
                               after:transition-all peer-checked:after:translate-x-full">
                </div>
                <span class="ml-3 text-sm text-gray-700">Деньги</span>
            </label>
        </div>

        <input
            type="number"
            min="1"
            name="size"
            required
            placeholder="Размер"
            class="mt-3 w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
        />
    </div>

    <!-- Время действия -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Время действия
        </label>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="text-xs text-gray-500">С</span>
                <input
                    type="datetime-local"
                    name="from_date"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition"
                >
            </div>

            <div>
                <span class="text-xs text-gray-500">По</span>
                <input
                    type="datetime-local"
                    name="to_date"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition"
                >
            </div>
        </div>
    </div>

    <!-- Кнопки -->
    <div class="flex items-center justify-between pt-4 border-t">
        <a href="{{ route('partner.my-shares.index') }}"
           class="flex items-center text-gray-500 hover:text-indigo-600 transition">
            <img src="/images/cabinet/left-arrow.svg" class="mr-2" alt="icon">
            вернуться
        </a>

        <button
            type="submit"
            class="bg-indigo-600 text-white px-6 py-2 rounded-lg
                       hover:bg-indigo-700 active:scale-95 transition">
            Добавить
        </button>
    </div>
</form>
