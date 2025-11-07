@extends('layouts.app')
@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="container products-list">
        <main class="max-w-7xl mx-auto px-4 pb-12">
            <!-- фильтры / поиск -->
            <div class="flex flex-col sm:flex-row gap-3 items-center justify-between mb-6">
                <div class="flex gap-3 w-full sm:w-auto">
                    <input type="text" placeholder="Поиск по названию"
                           class="w-full sm:w-80 px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    <select class="px-3 py-2 border rounded-md">
                        <option value="popular">Популярные</option>
                        <option value="price_asc">Цена ↑</option>
                        <option value="price_desc">Цена ↓</option>
                    </select>
                </div>
                <div class="text-sm text-gray-600">Показано <span class="font-medium">12</span> товаров</div>
            </div>

            <!-- сетка товаров -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- карточка товара -->
                @foreach($products as $product)
                    <a href="{{ route('product.show', ['slug' => $product->slug]) }}">
                        <article class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="relative">
                                <img src="{{ $product->mainImage->url }}"
                                     alt="Название товара 1"
                                     class="w-full h-48 object-cover" loading="lazy" />
                                <span class="absolute top-3 left-3 bg-indigo-600 text-white text-xs font-semibold px-2 py-1 rounded">Новинка</span>
                                <button aria-label="Добавить в избранное"
                                        class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm p-2 rounded-full shadow hover:bg-white">
                                    <!-- простая иконка сердечка -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.343 3.172 10.83a4 4 0 010-5.657z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="p-4 flex flex-col gap-3">
                                <h3 class="text-sm font-semibold leading-tight">{{ $product->name }}</h3>

                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-baseline gap-2">
                                        <div class="text-lg font-bold">{{ $product->price }}</div>
                                        <div class="text-sm text-gray-600">₸</div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700 transition">
                                            В корзину
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                                    <div>В наличии: <span class="font-medium text-gray-700">{{ $product->quantity }}</span></div>
                                    <div>Артикул: <span class="font-mono">{{ $product->sku }}</span></div>
                                </div>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        </main>
    </div>
@endsection
