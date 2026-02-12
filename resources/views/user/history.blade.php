@extends('user.user')
@section('content')
    <div class="w-full max-w-md mx-auto">
        <div class="flex border-b border-gray-200 justify-center">
            <button class="tab-btn py-2 px-4 text-blue-600 border-b-2 border-blue-600 font-medium" data-tab="balance">
                баланс
            </button>
            <button class="tab-btn py-2 px-4 text-gray-600 hover:text-blue-600" data-tab="shopping">
                покупки
            </button>
        </div>

        <div class="mt-4 px-3">
            <div id="balance" class="tab-content">
                @include('user.partials._balance')
            </div>

            <div id="shopping" class="tab-content hidden">
                @include('user.partials._shopping')
            </div>
        </div>
    </div>
@stop

@push('user_scripts')
    <script>
        const tabs = document.querySelectorAll(".tab-btn");
        const contents = document.querySelectorAll(".tab-content");

        tabs.forEach(tab => {
            tab.addEventListener("click", () => {
                // Сброс активной кнопки
                tabs.forEach(t => {
                    t.classList.remove("text-blue-600", "border-b-2", "border-blue-600", "font-medium");
                    t.classList.add("text-gray-600");
                });
                // Скрыть все контенты
                contents.forEach(c => c.classList.add("hidden"));

                // Активировать выбранную вкладку
                tab.classList.add("text-blue-600", "border-b-2", "border-blue-600", "font-medium");
                tab.classList.remove("text-gray-600");

                document.getElementById(tab.dataset.tab).classList.remove("hidden");
            });
        });
    </script>
@endpush
