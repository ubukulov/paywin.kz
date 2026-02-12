@extends('layouts.app')

@section('content')

    <div class="container product-page">
        <div class="max-w-5xl mx-auto px-4 py-6 sm:py-10">

            <h1 class="text-xl sm:text-2xl font-semibold mb-6">
                Корзина
            </h1>

            {{-- ================= EMPTY ================= --}}
            @if($cart->items->isEmpty())

                <div class="text-gray-600 text-lg">
                    Корзина пустая
                </div>

            @else

                {{-- ================= ITEMS ================= --}}
                <div class="space-y-4">

                    @foreach($cart->items as $item)

                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 bg-white p-4 rounded-2xl shadow">

                            {{-- IMAGE --}}
                            <img
                                src="{{ $item->product->mainImage->url ?? asset('images/no-image.png') }}"
                                class="w-full sm:w-24 h-44 sm:h-24 object-cover rounded-xl"
                            >

                            {{-- INFO --}}
                            <div class="flex-1">

                                <div class="text-base sm:text-lg font-medium leading-snug">
                                    {{ $item->product->name }}
                                </div>

                                {{-- QTY --}}
                                <div class="mt-3 flex items-center gap-3">

                                    <button
                                        class="w-10 h-10 rounded-lg bg-gray-100 active:bg-gray-200 text-lg"
                                        onclick="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})">
                                        −
                                    </button>

                                    <div class="min-w-[32px] text-center font-medium text-base">
                                        {{ $item->quantity }}
                                    </div>

                                    <button
                                        class="w-10 h-10 rounded-lg bg-gray-100 active:bg-gray-200 text-lg"
                                        onclick="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})">
                                        ＋
                                    </button>

                                </div>
                            </div>

                            {{-- PRICE + DELETE --}}
                            <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2">

                                <div class="text-lg font-bold whitespace-nowrap">
                                    {{ number_format($item->total,0,'.',' ') }} ₸
                                </div>

                                <button
                                    onclick="deleteItem({{ $item->id }})"
                                    class="text-red-500 text-sm hover:underline">
                                    Удалить
                                </button>

                            </div>

                        </div>

                    @endforeach

                </div>


                {{-- ================= GIFTS ================= --}}
                @if($gifts->isNotEmpty())

                    <div class="mt-8 p-5 rounded-2xl bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 shadow-sm">

                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-xl">
                                🎁
                            </div>

                            <h2 class="text-lg sm:text-xl font-semibold text-indigo-700">
                                Вы участвуете в розыгрыше подарка
                            </h2>
                        </div>

                        <p class="text-gray-700 mb-4 text-sm sm:text-base">
                            После оплаты будет разыгран <b>один</b> подарок:
                        </p>

                        <div class="relative overflow-hidden">

                            {{-- стрелка влево --}}
                            <button
                                onclick="prevGift()"
                                class="absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full w-10 h-10 flex items-center justify-center">
                                ‹
                            </button>

                            {{-- стрелка вправо --}}
                            <button
                                onclick="nextGift()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full w-10 h-10 flex items-center justify-center">
                                ›
                            </button>

                            {{-- SLIDER --}}
                            <div class="w-full overflow-hidden">

                                <div
                                    id="giftSlider"
                                    class="flex transition-transform duration-300 ease-in-out">

                                    @foreach($gifts as $gift)

                                        {{-- ВСЕГДА 100% ширины --}}
                                        <div class="w-full flex-shrink-0 px-1">

                                            <div class="p-4 bg-white border rounded-xl shadow-sm">

                                                @if($gift->image)
                                                    <img src="{{ $gift->image }}"
                                                         class="w-full h-40 object-cover rounded-lg mb-3">
                                                @endif

                                                <div class="text-lg font-medium">
                                                    {{ $gift->title }}
                                                </div>

                                                <div class="text-gray-600 text-sm mt-1">
                                                    {{ $gift->description }}
                                                </div>

                                                <div class="mt-3 text-indigo-500 text-sm">
                                                    ⭐ Возможный приз
                                                </div>

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>
                        </div>


                        <p class="mt-4 text-xs sm:text-sm text-gray-500 text-center">
                            🎲 Победитель определяется автоматически после оплаты
                        </p>
                    </div>

                @endif



                {{-- ================= TOTAL ================= --}}
                <div class="mt-8 p-5 bg-white rounded-2xl shadow flex flex-col sm:flex-row items-center justify-between gap-4">

                    <div class="text-lg sm:text-xl font-semibold">
                        Итого: {{ number_format($cart->total,0,'.',' ') }} ₸
                    </div>

                    <a href="{{ route('checkout.index') }}"
                       class="w-full sm:w-auto text-center px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                        Оформить заказ
                    </a>

                </div>

            @endif

        </div>
    </div>



    {{-- ================= JS ================= --}}
    <script>
        function updateQty(itemId, qty) {
            if (qty < 1) qty = 1;

            fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    item_id: itemId,
                    quantity: qty
                })
            }).then(() => location.reload());
        }

        function deleteItem(id) {
            fetch('/cart/item/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload());
        }
    </script>



    {{-- ================= GIFT SLIDER ================= --}}
    <script>
        let currentGift = 0;

        function updateGiftSlide() {
            const slider = document.getElementById('giftSlider');
            slider.style.transform = `translateX(-${currentGift * 100}%)`;
        }

        function nextGift() {
            const total = document.querySelectorAll('#giftSlider > div').length;
            if (currentGift < total - 1) {
                currentGift++;
                updateGiftSlide();
            }
        }

        function prevGift() {
            if (currentGift > 0) {
                currentGift--;
                updateGiftSlide();
            }
        }
    </script>



    {{-- UX улучшения --}}
    <style>
        button {
            -webkit-tap-highlight-color: transparent;
        }
    </style>

@endsection
