@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="mypromo">
        <div class="w-full max-w-md mx-auto mt-10">
            <div class="flex border-b border-gray-200 justify-center">
                <button class="tab-btn py-2 px-4 text-blue-600 border-b-2 border-blue-600 font-medium" data-tab="gift">
                    приз
                </button>
                <button class="tab-btn py-2 px-4 text-gray-600 hover:text-blue-600" data-tab="discount">
                    скидка
                </button>
                <button class="tab-btn py-2 px-4 text-gray-600 hover:text-blue-600" data-tab="cashback">
                    cashback
                </button>
                <button class="tab-btn py-2 px-4 text-gray-600 hover:text-blue-600" data-tab="promo">
                    промокод
                </button>
            </div>

            <div class="mt-4 px-3">
                <div id="gift" class="tab-content">
                    @include('partner.shares.types.gift')
                </div>
                <div id="discount" class="tab-content hidden">
                    @include('partner.shares.types.discount')
                </div>
                <div id="cashback" class="tab-content hidden">
                    @include('partner.shares.types.cashback')
                </div>
                <div id="promo" class="tab-content hidden">
                    @include('partner.shares.types.promo')
                </div>
            </div>
        </div>
    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

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
