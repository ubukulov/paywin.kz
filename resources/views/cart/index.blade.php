@extends('layouts.app')

@section('content')
    <div class="container product-page">
        <div class="max-w-5xl mx-auto px-4 py-10">

            <h1 class="text-2xl font-semibold mb-6">Корзина</h1>

            @if($cart->items->isEmpty())
                <div class="text-gray-600 text-lg">Корзина пустая</div>
            @else
                <div class="space-y-4">
                    @foreach($cart->items as $item)
                        <div class="flex items-center gap-4 bg-white p-4 rounded-xl shadow">

                            {{-- картинка --}}
                            <img src="{{ $item->product->mainImage->url ?? asset('images/no-image.png') }}"
                                 class="w-24 h-24 object-cover rounded">

                            <div class="flex-1">
                                <div class="text-lg font-medium">{{ $item->product->name }}</div>

                                {{-- количество --}}
                                <div class="mt-2 flex items-center gap-2">
                                    <button class="px-3 py-1 bg-gray-200 rounded"
                                            onclick="updateQty({{ $item->id }}, {{ $item->quantity - 1 }})">−</button>

                                    <div class="px-3">{{ $item->quantity }}</div>

                                    <button class="px-3 py-1 bg-gray-200 rounded"
                                            onclick="updateQty({{ $item->id }}, {{ $item->quantity + 1 }})">＋</button>
                                </div>
                            </div>

                            <div class="text-lg font-bold whitespace-nowrap">
                                {{ number_format($item->total,0,'.',' ') }} ₸
                            </div>

                            <button onclick="deleteItem({{ $item->id }})" class="text-red-600 text-sm">
                                Удалить
                            </button>
                        </div>
                    @endforeach
                </div>

                @if($gifts->isNotEmpty())
                    <div class="mt-6 p-5 rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 shadow-sm">

                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                🎁
                            </div>
                            <h2 class="text-xl font-semibold text-indigo-700">
                                Вы участвуете в розыгрыше подарка
                            </h2>
                        </div>

                        <p class="text-gray-700 mb-4">
                            После оплаты будет разыгран <b>один</b> подарок:
                        </p>

                        <div class="relative mx-auto">

                            {{-- кнопка влево --}}
                            <button onclick="prevGift()"
                                    class="absolute -left-5 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full w-9 h-9 flex items-center justify-center">
                                ‹
                            </button>

                            {{-- кнопка вправо --}}
                            <button onclick="nextGift()"
                                    class="absolute -right-5 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full w-9 h-9 flex items-center justify-center">
                                ›
                            </button>

                            {{-- SLIDER --}}
                            <div class="overflow-hidden">
                                <div id="giftSlider"
                                     class="flex transition-transform duration-300 ease-in-out">

                                    @foreach($gifts as $gift)
                                        <div class="min-w-full px-2">
                                            <div class="p-4 bg-white border rounded-xl shadow-sm" style="margin: 0 20px;">

                                                @if($gift->image)
                                                    <img src="{{ $gift->image }}"
                                                         class="w-full h-40 object-cover rounded-lg mb-3">
                                                @endif

                                                <div class="text-lg font-medium text-gray-900">
                                                    {{ $gift->title }}
                                                </div>

                                                <div class="text-gray-600 mt-1 text-sm">
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

                        <p class="mt-4 text-sm text-gray-500 text-center">
                            🎲 Победитель определяется автоматически после оплаты
                        </p>
                    </div>
                @endif




                {{-- ИТОГО --}}
                <div class="mt-6 p-4 bg-white rounded-xl shadow">
                    <div class="text-xl font-semibold">
                        Итого: {{ number_format($cart->total,0,'.',' ') }} ₸
                    </div>
                    <a href="{{ route('checkout.index') }}" class="mt-3 inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg">Оформить заказ</a>
                </div>
            @endif
        </div>
    </div>


    <script>
        function updateQty(itemId, qty) {
            if (qty < 1) qty = 1;

            fetch('/cart/update', {
                method: 'POST',
                headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({ item_id: itemId, quantity: qty })
            })
                .then(() => location.reload());
        }

        function deleteItem(id) {
            fetch('/cart/item/' + id, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            }).then(() => location.reload());
        }
    </script>

    <script>
        let currentGift = 0;

        function updateGiftSlide() {
            const slider = document.getElementById('giftSlider');
            slider.style.transform = `translateX(-${currentGift * 100}%)`;
        }

        function nextGift() {
            const total = document.querySelectorAll('#giftSlider > div').length;
            currentGift = (currentGift + 1) % total;
            updateGiftSlide();
        }

        function prevGift() {
            const total = document.querySelectorAll('#giftSlider > div').length;
            currentGift = (currentGift - 1 + total) % total;
            updateGiftSlide();
        }
    </script>

@endsection
