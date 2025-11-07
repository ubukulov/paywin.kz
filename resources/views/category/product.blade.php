@extends('layouts.app')
@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <div class="container product-page">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- ЛЕВАЯ КОЛОНКА: Галерея --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow overflow-hidden">
                        <div class="relative">
                            {{-- Основное изображение --}}
                            <img
                                id="product-main-image"
                                src="{{ $product->mainImage->url ?? ($product->images->first()->url ?? asset('images/no-image.png')) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-[480px] object-contain bg-gray-100"
                                loading="lazy"
                            >

                            {{-- Кнопки prev / next (на больших экранах) --}}
                            <button onclick="galleryPrev()" class="hidden lg:flex items-center justify-center absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 p-2 rounded-full shadow">
                                ‹
                            </button>
                            <button onclick="galleryNext()" class="hidden lg:flex items-center justify-center absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 p-2 rounded-full shadow">
                                ›
                            </button>
                        </div>

                        {{-- Тумбнейлы --}}
                        <div class="px-4 py-3 border-t">
                            <div class="flex gap-3 overflow-x-auto pb-1">
                                @forelse($product->images as $i => $image)
                                    <button
                                        type="button"
                                        class="thumbnail shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition"
                                        data-index="{{ $i }}"
                                        onclick="selectImage({{ $i }})"
                                        aria-label="Показать изображение {{ $i + 1 }}"
                                    >
                                        <img src="{{ $image->url }}" alt="thumb-{{ $i }}" class="w-full h-full object-cover" loading="lazy">
                                    </button>
                                @empty
                                    <div class="text-sm text-gray-500">Изображений нет</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Короткие карточки доп.информации (опционально) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="bg-white p-4 rounded-xl shadow-sm">
                            <h4 class="text-xs text-gray-500">Быстрая информация</h4>
                            <div class="mt-2 text-sm text-gray-700">
                                <div>Артикул: <span class="font-mono">{{ $product->sku }}</span></div>
                                <div>В наличии: <span class="font-medium">{{ $product->quantity }}</span></div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-xl shadow-sm">
                            <h4 class="text-xs text-gray-500">Доставка</h4>
                            <div class="mt-2 text-sm text-gray-700">
                                Доставка по Казахстану 1–3 дня. Бесплатная доставка от 50 000 ₸.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ПРАВАЯ КОЛОНКА: Информация и действие --}}
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>

                        {{-- рейтинг или метки --}}
                        <div class="flex items-center gap-3 mt-2">
                            <div class="text-sm text-gray-500">Категория: <span class="font-medium">{{ $product->category->title ?? '—' }}</span></div>
                            {{-- пример метки --}}
                            @if($product->active)
                                <span class="ml-auto inline-flex items-center bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">Опубликован</span>
                            @endif
                        </div>

                        {{-- Цена --}}
                        <div class="mt-4 flex items-baseline gap-3">
                            <div class="text-3xl font-bold">{{ number_format($product->price, 0, '.', ' ') }}</div>
                            <div class="text-xl text-gray-600">₸</div>
                            @if($product->old_price ?? false)
                                <div class="text-sm line-through text-gray-400 ml-3">{{ number_format($product->old_price,0,'.',' ') }} ₸</div>
                            @endif
                        </div>

                        {{-- Кнопка добавить в корзину (пример формы) --}}
                        <form action="#" method="POST" class="mt-6 flex gap-3 items-center">
                            @csrf
                            <div class="flex items-center border rounded-md overflow-hidden">
                                <button type="button" onclick="decrementQty()" class="px-3 py-2">−</button>
                                <input id="cart-qty" name="quantity" value="1" min="1" max="{{ max(1, $product->quantity) }}" type="number" class="w-16 text-center px-2 py-2 outline-none" />
                                <button type="button" onclick="incrementQty()" class="px-3 py-2">＋</button>
                            </div>

                            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">В корзину</button>

                            {{-- ещё кнопка купить в 1 клик --}}
                            <button type="button" class="ml-2 text-sm text-indigo-600 underline">Купить в 1 клик</button>
                        </form>

                        {{-- Кнопки соц/поделиться/избранное --}}
                        <div class="mt-4 flex gap-3 text-sm">
                            <button class="px-3 py-1 rounded-md border">Поделиться</button>
                            <button class="px-3 py-1 rounded-md border">Добавить в избранное</button>
                        </div>
                    </div>

                    {{-- Описание --}}
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <h2 class="text-lg font-semibold mb-2">Описание</h2>
                        <div class="prose max-w-none text-sm text-gray-700">
                            {!! $product->description !!}
                        </div>
                    </div>

                    {{-- Характеристики / meta --}}
                    @if($product->meta)
                        <div class="bg-white p-6 rounded-2xl shadow-sm">
                            <h3 class="text-sm font-semibold mb-2">Характеристики</h3>
                            <pre class="text-xs text-gray-600 whitespace-pre-wrap">{{ is_array($product->meta) ? json_encode($product->meta, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) : $product->meta }}</pre>
                        </div>
                    @endif

                    {{-- Опционально: похожие товары --}}
                    @if(isset($related) && $related->isNotEmpty())
                        <div>
                            <h3 class="text-lg font-semibold mb-3">Похожие товары</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($related as $r)
                                    <a href="{{ route('product.show', $r) }}" class="block bg-white rounded-xl p-3 shadow-sm hover:shadow-md">
                                        <img src="{{ $r->mainImage->url ?? asset('images/no-image.png') }}" alt="{{ $r->name }}" class="w-full h-28 object-cover rounded" />
                                        <div class="mt-2 text-sm font-medium">{{ $r->name }}</div>
                                        <div class="text-sm text-gray-600">{{ number_format($r->price,0,'.',' ') }} ₸</div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Скрипт галереи и управления кол-вом (минимальный, без библиотек) --}}
        <script>
            // Собираем картинки из blade в массив
            const productImages = [
                @foreach($product->images as $img)
                    "{{ $img->url }}",
                @endforeach
            ];

            let currentIndex = 0;
            const mainImage = document.getElementById('product-main-image');

            // Если есть mainImage -> установить индекс на него
            @if($product->mainImage)
                currentIndex = productImages.indexOf("{{ $product->mainImage->url }}");
            if (currentIndex < 0) currentIndex = 0;
            @endif

            function selectImage(index) {
                currentIndex = index;
                mainImage.src = productImages[currentIndex] || mainImage.src;
                // подсветка миниатюр
                document.querySelectorAll('.thumbnail').forEach((el, i) => {
                    el.classList.toggle('ring-2 ring-indigo-500', i === currentIndex);
                });
            }

            function galleryNext() {
                if (!productImages.length) return;
                currentIndex = (currentIndex + 1) % productImages.length;
                selectImage(currentIndex);
            }

            function galleryPrev() {
                if (!productImages.length) return;
                currentIndex = (currentIndex - 1 + productImages.length) % productImages.length;
                selectImage(currentIndex);
            }

            // thumbnails initial highlight
            document.addEventListener('DOMContentLoaded', () => {
                selectImage(currentIndex);
            });

            // qty controls
            function incrementQty() {
                const el = document.getElementById('cart-qty');
                const max = parseInt(el.max) || 9999;
                el.value = Math.min(max, parseInt(el.value || 0) + 1);
            }
            function decrementQty() {
                const el = document.getElementById('cart-qty');
                el.value = Math.max(1, parseInt(el.value || 1) - 1);
            }
        </script>
    </div>
@endsection
