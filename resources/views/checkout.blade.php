@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-semibold mb-6">Оформление заказа</h1>

        <form action="{{ route('checkout.store') }}" method="POST"
              class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white p-6 rounded-xl shadow">
            @csrf

            <div>
                <h2 class="font-medium mb-4">Данные покупателя</h2>

                <label class="block mb-3">
                    <span class="text-sm">Имя</span>
                    <input type="text" name="name" required class="w-full border rounded p-2 mt-1">
                </label>

                <label class="block mb-3">
                    <span class="text-sm">Телефон</span>
                    <input type="text" name="phone" required class="w-full border rounded p-2 mt-1">
                </label>

                <label class="block mb-3">
                    <span class="text-sm">Адрес доставки</span>
                    <textarea name="address" rows="3" required
                              class="w-full border rounded p-2 mt-1"></textarea>
                </label>
            </div>

            <div>
                <h2 class="font-medium mb-4">Ваш заказ</h2>

                <div class="space-y-3">
                    @foreach($cart->items as $item)
                        <div class="flex justify-between border-b pb-1">
                            <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                            <span>{{ number_format($item->total) }} ₸</span>
                        </div>
                    @endforeach
                </div>

                <div class="text-xl font-semibold mt-4">
                    Итого: {{ number_format($cart->total) }} ₸
                </div>

                <button class="mt-6 w-full bg-indigo-600 text-white py-2 rounded-lg">
                    Подтвердить заказ
                </button>
            </div>
        </form>
    </div>
@endsection
