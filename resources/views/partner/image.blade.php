@extends('partner.partner')

@section('content')
    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="mb-8 text-center md:text-left">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight uppercase">
                Добавление <span class="text-indigo-600">фотографий</span>
            </h1>
            <p class="text-gray-400 text-sm font-medium mt-2">Загрузите изображения вашего заведения для витрины</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <form action="{{ route('partner.imageStore') }}" method="post" enctype="multipart/form-data" class="p-8 md:p-12">
                @csrf

                <div class="mb-10">
                    <label for="inputGroupFile01" class="group relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50/50 hover:bg-indigo-50/50 hover:border-indigo-300 transition-all cursor-pointer overflow-hidden">

                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <div class="w-16 h-16 mb-4 rounded-2xl bg-white shadow-sm flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform duration-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>

                            <p class="mb-2 text-sm text-gray-700 font-black uppercase tracking-widest">Выберите изображения</p>
                            <p class="text-xs text-gray-400 font-medium">PNG, JPG или WEBP (макс. 10 шт.)</p>
                        </div>

                        <input type="file" name="images[]" id="inputGroupFile01" required multiple accept="image/*"
                               class="absolute inset-0 opacity-0 cursor-pointer"
                               onchange="updateFileName(this)">

                        <div id="file-count" class="hidden absolute bottom-4 px-4 py-2 bg-indigo-600 text-white text-[10px] font-black uppercase rounded-full shadow-lg">
                            Файлы выбраны
                        </div>
                    </label>
                </div>

                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <button type="submit"
                            class="w-full md:w-auto px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-lg shadow-indigo-200 transition-all active:scale-95">
                        Сохранить фото
                    </button>

                    <a href="{{ route('partner.cabinet') }}"
                       class="w-full md:w-auto px-10 py-4 bg-white text-gray-400 hover:text-gray-600 font-black uppercase tracking-widest text-[10px] rounded-2xl border border-gray-100 hover:bg-gray-50 transition-all text-center">
                        Вернуться назад
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-8 p-6 bg-blue-50 rounded-3xl border border-blue-100 flex items-start gap-4">
            <div class="text-blue-500 mt-1">
                <i class="fas fa-info-circle"></i>
            </div>
            <p class="text-xs text-blue-700 font-medium leading-relaxed">
                Совет: Используйте качественные фотографии интерьера и экстерьера. Хорошие снимки повышают доверие клиентов на 40% и увеличивают количество заказов.
            </p>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            const countLabel = document.getElementById('file-count');
            if (input.files.length > 0) {
                countLabel.innerText = `Выбрано файлов: ${input.files.length}`;
                countLabel.classList.remove('hidden');
            } else {
                countLabel.classList.add('hidden');
            }
        }
    </script>
@stop
