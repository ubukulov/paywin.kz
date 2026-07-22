<form action="{{ route('partner.my-shares.update', ['my_share' => $share->id]) }}"
      method="POST"
      enctype="multipart/form-data"
      class="max-w-2xl mx-auto rounded-2xl space-y-6 px-3">

    @csrf
    @method('PUT')

    <input type="hidden" name="type" value="gift">

    {{-- Загрузка изображения --}}
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">Изображение</label>

        <div class="flex items-center gap-4">
            {{-- Область предпросмотра --}}
            <div id="image-preview-container" class="w-20 h-20 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden bg-gray-50">
                <img id="image-preview" @if(isset($share->data['image']) && $share->data['image']) src="{{ asset('storage/' . $share->data['image']) }}" @else src="{{ asset('images/no-image.png') }}" @endif
                class="w-full h-full object-cover @if(!$share->data['image']) hidden @endif">
                <i id="upload-icon" class="fas fa-camera text-gray-300 text-xl"></i>
            </div>

            {{-- Кастомный инпут --}}
            <div class="flex-1">
                <input
                    type="file"
                    name="image"
                    id="image-input"
                    accept="image/*"
                    class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-lg file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-600
                hover:file:bg-indigo-100 transition"
                >
                <p class="mt-1 text-[10px] text-gray-400 italic">Рекомендуемый размер: 800x800px (JPG, PNG)</p>
            </div>
        </div>
    </div>

    {{-- Название --}}
    <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-600">Название приза</label>
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
            value="{{ $share->count }}"
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
                value="{{ $share->data['from_order'] }}"
                class="w-full rounded-lg border border-gray-300 px-4 py-2
                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200
                       outline-none transition"
            />

            <span class="text-gray-500">до</span>
            <input
                type="number"
                min="1"
                name="to_order"
                value="{{ $share->data['to_order'] }}"
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
            value="{{ $share->data['c_winning'] }}"
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
