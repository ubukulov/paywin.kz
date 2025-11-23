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

            fetch('/api/cart/update', {
                method: 'POST',
                headers: {'Content-Type': 'application/json','X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: JSON.stringify({ item_id: itemId, quantity: qty })
            })
                .then(() => location.reload());
        }

        function deleteItem(id) {
            fetch('/api/cart/item/' + id, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            }).then(() => location.reload());
        }
    </script>

@endsection
