<form action="{{ route('partner.my-shares.store') }}" method="post"
      class="max-w-2xl mx-auto rounded-2xl space-y-6">

    @csrf
    <input type="hidden" name="type" value="share">

    {{-- Название --}}
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">Название приза</label>
        <input
            name="title"
            required
            placeholder="Введите..."
            class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
        />
    </div>

    {{-- Количество --}}
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">Количество</label>
        <input
            type="number"
            min="1"
            name="cnt"
            required
            class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
        />
    </div>

    {{-- При заказе --}}
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">При заказе</label>

        <div class="flex gap-3 items-center">
            <span class="text-gray-500">от</span>
            <input
                type="number"
                min="1"
                name="from_order"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
            />

            <span class="text-gray-500">до</span>
            <input
                type="number"
                min="1"
                name="to_order"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
            />
        </div>
    </div>

    {{-- Коэффициент --}}
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">Коэф. выигрыша</label>
        <input
            type="number"
            min="1"
            name="c_winning"
            required
            class="w-full rounded-lg border border-gray-300 px-4 py-2
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

    {{-- Кнопки --}}
    <div class="flex justify-between items-center pt-4">
        <a href="{{ route('partner.my-shares.index') }}"
           class="flex items-center gap-2 text-gray-500 hover:text-gray-800">
            ← Вернуться
        </a>

        <button
            type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
        >
            Добавить
        </button>
    </div>

</form>
