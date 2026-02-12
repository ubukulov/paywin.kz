<form action="{{ route('partner.my-shares.update', ['my_share' => $share->id]) }}"
      method="post"
      class="max-w-2xl mx-auto rounded-2xl space-y-6 px-3">
    @csrf
    @method('PUT')
    <input type="hidden" name="type" value="cashback">

    {{-- Название --}}
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">Название кэшбека</label>
        <input
            name="title"
            value="{{ $share->title }}"
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
            value="{{ $share->cnt }}"
            required
            class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
        />
    </div>

    {{-- Размер cashback --}}
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">Размер cashback (%)</label>
        <input
            type="number"
            min="1"
            name="size"
            value="{{ $share->size }}"
            required
            class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
        />
    </div>

    {{-- При заказе --}}
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">При заказе</label>

        <div class="grid grid-cols-2 gap-4">
            <input
                type="number"
                min="1"
                name="from_order"
                value="{{ $share->from_order }}"
                placeholder="от"
                required
                class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
            />

            <input
                type="number"
                min="1"
                name="to_order"
                value="{{ $share->to_order }}"
                placeholder="до"
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
            value="{{ $share->c_winning }}"
            required
            class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
        />
    </div>

    {{-- Даты --}}{{--
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">Время действия</label>

        <div class="grid grid-cols-2 gap-4">
            <input
                type="date"
                name="from_date"
                value="{{ date('Y-m-d') }}"
                class="border rounded-lg px-3 py-2"
            />

            <input
                type="date"
                name="to_date"
                value="{{ date('Y-m-d') }}"
                class="border rounded-lg px-3 py-2"
            />
        </div>
    </div>--}}

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
                    value="{{ $share->from_date }}"
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
                    value="{{ $share->to_date }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2
                               focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                               outline-none transition"
                >
            </div>
        </div>
    </div>

    {{-- Кнопки --}}
    <div class="flex justify-between items-center pt-6 border-t">
        <a href="{{ route('partner.my-shares.index') }}"
           class="text-gray-500 hover:text-gray-700 transition">
            ← Вернуться
        </a>

        <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-medium transition">
            Обновить
        </button>
    </div>
</form>
