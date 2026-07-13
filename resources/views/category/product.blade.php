@extends('layouts.app')

@section('og_title', $product->name)
@section('og_description', "Купи {$product->name} на Paywin и получи гарантированный приз!")
@section('og_image', $product->mainImage->url ?? asset('img/no-image.png'))

@section('content')
    @php
        // 1. Извлекаем ссылку на видео из JSON (data или meta)
        $videoUrl = null;
        if ($product->data && is_array($product->data) && !empty($product->data['system_video_url'])) {
            $videoUrl = $product->data['system_video_url'];
        } elseif ($product->meta && is_array($product->meta) && !empty($product->meta['system_video_url'])) {
            $videoUrl = $product->meta['system_video_url'];
        }

        // 2. Парсим ID видео для корректного Embed iframe
        $embedUrl = null;
        $youtubeId = null;
        if ($videoUrl) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/|youtube\.com/shorts/)([^"&?/ ]{11})%i', $videoUrl, $match)) {
                $youtubeId = $match[1];
                // origin параметр защищает от CORS блокировок в браузере
                $embedUrl = "https://www.youtube.com/embed/" . $youtubeId . "?enablejsapi=1&origin=" . urlencode(url('/'));
            }
        }
    @endphp

    <div class="product-page">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- ЛЕВАЯ КОЛОНКА: Галерея + Видео --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow overflow-hidden">
                        <div class="relative h-[380px] bg-gray-100 flex items-center justify-center">

                            {{-- Основное изображение товара --}}
                            <img
                                id="product-main-image"
                                src="{{ $product->mainImage->url ?? ($product->images->first()->url ?? asset('images/no-image.png')) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-contain"
                                loading="lazy"
                            >

                            {{-- Плеер для видеообзора (по умолчанию скрыт через hidden) --}}
                            @if($embedUrl)
                                <div id="product-video-container" class="absolute inset-0 hidden bg-black w-full h-full">
                                    <iframe
                                        id="product-iframe"
                                        class="w-full h-full"
                                        src="{{ $embedUrl }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            @endif

                            {{-- Стрелки навигации слайдера --}}
                            <button type="button" onclick="galleryPrev()"
                                    class="hidden lg:flex items-center justify-center absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 w-10 h-10 rounded-full shadow z-10 font-bold text-xl outline-none hover:bg-white transition">
                                ‹
                            </button>
                            <button type="button" onclick="galleryNext()"
                                    class="hidden lg:flex items-center justify-center absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 w-10 h-10 rounded-full shadow z-10 font-bold text-xl outline-none hover:bg-white transition">
                                ›
                            </button>
                        </div>

                        {{-- Миниатюры (Тумбнейлы) --}}
                        <div class="px-4 py-3 border-t">
                            <div class="flex gap-3 overflow-x-auto pb-1 scrollbar-none">
                                @php $imgCount = 0; @endphp
                                @foreach($product->images as $i => $image)
                                    <button
                                        type="button"
                                        class="thumbnail shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition outline-none"
                                        data-index="{{ $i }}"
                                        onclick="selectImage({{ $i }})"
                                        aria-label="Показать изображение {{ $i + 1 }}"
                                    >
                                        <img src="{{ $image->url }}" alt="thumb-{{ $i }}" class="w-full h-full object-cover" loading="lazy">
                                    </button>
                                    @php $imgCount++; @endphp
                                @endforeach

                                {{-- Кнопка видеообзора в самом конце списка картинок --}}
                                @if($embedUrl)
                                    <button
                                        type="button"
                                        class="thumbnail shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition relative bg-slate-900 border-gray-200 outline-none"
                                        data-index="{{ $imgCount }}"
                                        onclick="selectImage({{ $imgCount }})"
                                        aria-label="Показать видеообзор"
                                    >
                                        @if($youtubeId)
                                            <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg" class="w-full h-full object-cover opacity-60" alt="Video cover">
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 text-white text-xl">
                                            <i class="fas fa-play text-red-500 animate-pulse"></i>
                                        </div>
                                    </button>
                                @endif

                                @if($imgCount === 0 && !$embedUrl)
                                    <div class="text-sm text-gray-500 py-2 font-medium">Изображений нет</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ПРАВАЯ КОЛОНКА: Информация и действие --}}
                <div class="space-y-6">
                    <div class="bg-white p-3 rounded-2xl shadow-sm">
                        <h1 class="text-2xl font-semibold text-gray-900 leading-tight">{{ $product->name }}</h1>

                        <div class="flex items-center gap-3 mt-2">
                            <div class="text-sm text-gray-500">Категория: <span class="font-medium text-gray-800">{{ $product->category->name ?? '—' }}</span></div>
                        </div>

                        {{-- Цены --}}
                        <div class="mt-4 flex items-baseline gap-1.5">
                            <div class="text-3xl font-black text-gray-900">{{ number_format($product->price, 0, '.', ' ') }}</div>
                            <div class="text-xl text-gray-600 font-bold">₸</div>
                            @if($product->old_price ?? false)
                                <div class="text-sm line-through text-gray-400 ml-3 font-medium">{{ number_format($product->old_price,0,'.',' ') }} ₸</div>
                            @endif
                        </div>

                        {{-- Форма корзины и быстрого заказа --}}
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-6 flex gap-3 items-center" id="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                <button type="button" onclick="decrementQty()" class="px-3 py-2 font-bold text-gray-500 hover:bg-gray-100 transition">−</button>
                                <input id="cart-qty" name="quantity" value="1" min="1" max="{{ max(1, $product->quantity) }}" type="number" class="w-12 text-center bg-transparent font-bold text-gray-800 outline-none p-2"/>
                                <button type="button" onclick="incrementQty()" class="px-3 py-2 font-bold text-gray-500 hover:bg-gray-100 transition">＋</button>
                            </div>

                            @if(Auth::check())
                                <button type="button" onclick="quickPurchase()" class="px-6 py-2.5 bg-orange-500 text-white font-black rounded-xl hover:bg-orange-600 transition shadow-sm">
                                    Купить
                                </button>
                                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                                    В корзину
                                </button>
                            @else
                                <a class="px-6 py-2.5 bg-orange-500 text-white font-black rounded-xl hover:bg-orange-600 transition text-center shadow-sm" href="{{ route('login') }}">Купить</a>
                                <a class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition text-center shadow-sm" href="{{ route('login') }}">В корзину</a>
                            @endif
                        </form>

                        {{-- Блок предзаказа --}}
                        @if($product->is_preorder && $product->delivery_days)
                            <div class="mt-6 flex items-center p-4 bg-amber-50 border border-amber-100 rounded-2xl shadow-xs animate__animated animate__fadeIn">
                                <div class="flex-shrink-0 bg-amber-100 p-2 rounded-lg text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-amber-700 font-medium">
                                        Ожидаемое поступление:
                                        <span class="font-black text-gray-900">{{ now()->addDays($product->delivery_days)->translatedFormat('d F Y') }} г.</span>
                                    </p>
                                </div>
                            </div>
                        @endif

                        @include('category.block_product_shares')
                        @include('category.block_product_paywin_bonus')
                        @include('category.block_product_delivery')
                        @include('category.block_product_referral')
                    </div>
                </div>
            </div>

            @include('category.block_product_desc')

            <div class="mt-4">
                @include('category.block_product_reviews')
            </div>
        </div>

        {{-- Скрипты галереи и управления заказами --}}
        <script>
            // Массив путей картинок
            const productImages = [
                @foreach($product->images as $img)
                    "{{ $img->url }}",
                @endforeach
            ];

            // Наличие видео на странице
            const hasVideo = {!! $embedUrl ? 'true' : 'false' !!};
            const totalElementsCount = productImages.length + (hasVideo ? 1 : 0);

            let currentIndex = 0;
            const mainImage = document.getElementById('product-main-image');
            const videoContainer = document.getElementById('product-video-container');
            const videoIframe = document.getElementById('product-iframe');

            @if($product->mainImage)
                currentIndex = productImages.indexOf("{{ $product->mainImage->url }}");
            if (currentIndex < 0) currentIndex = 0;
            @endif

            function selectImage(index) {
                currentIndex = index;

                // Если выбран индекс видео (оно всегда в конце)
                if (hasVideo && currentIndex === productImages.length) {
                    if (mainImage) mainImage.classList.add('hidden');
                    if (videoContainer) videoContainer.classList.remove('hidden');
                } else {
                    // Если выбрана обычная картинка
                    if (mainImage) {
                        mainImage.classList.remove('hidden');
                        if (productImages[currentIndex]) {
                            mainImage.src = productImages[currentIndex];
                        }
                    }
                    if (videoContainer) {
                        videoContainer.classList.add('hidden');
                        // Стопаем видео через postMessage, чтобы звук не накладывался на фотки
                        if (videoIframe && videoIframe.contentWindow) {
                            videoIframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                        }
                    }
                }

                // Смена стилей рамок у миниатюр
                document.querySelectorAll('.thumbnail').forEach((el, i) => {
                    if (i === currentIndex) {
                        el.classList.add('ring-2', 'ring-indigo-500', 'border-transparent');
                    } else {
                        el.classList.remove('ring-2', 'ring-indigo-500', 'border-transparent');
                    }
                });
            }

            function galleryNext() {
                if (!totalElementsCount) return;
                currentIndex = (currentIndex + 1) % totalElementsCount;
                selectImage(currentIndex);
            }

            function galleryPrev() {
                if (!totalElementsCount) return;
                currentIndex = (currentIndex - 1 + totalElementsCount) % totalElementsCount;
                selectImage(currentIndex);
            }

            document.addEventListener('DOMContentLoaded', () => {
                selectImage(currentIndex);
            });

            // Кнопки каунтера
            function incrementQty() {
                const el = document.getElementById('cart-qty');
                const max = parseInt(el.max) || 9999;
                el.value = Math.min(max, parseInt(el.value || 0) + 1);
            }

            function decrementQty() {
                const el = document.getElementById('cart-qty');
                el.value = Math.max(1, parseInt(el.value || 1) - 1);
            }

            // Добавление в корзину через AJAX
            document.getElementById('add-to-cart-form').addEventListener('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                fetch("{{ route('cart.add') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const counter = document.getElementById('cart-count');
                            if (counter) counter.innerText = data.total_items ?? 0;
                            if (typeof window.showToast === 'function') window.showToast(data.message);
                        } else {
                            if (typeof window.showToast === 'function') window.showToast(data.message, "error");
                        }
                    });
            });

            // Мгновенная сквозная покупка (в 1 клик с очисткой корзины)
            function quickPurchase() {
                const form = document.getElementById('add-to-cart-form');
                if (!form) return;

                let formData = new FormData(form);

                // Шлем запрос на специальный метод экспресс-чекаута
                fetch("{{ route('checkout.instant') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.redirect_url) {
                            // Переходим на оформление, где отобразится ТОЛЬКО этот товар
                            window.location.href = data.redirect_url;
                        } else {
                            if (typeof window.showToast === 'function') {
                                window.showToast(data.message || "Ошибка оформления", "error");
                            } else {
                                alert(data.message);
                            }
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Произошла ошибка при обработке экспресс-заказа");
                    });
            }
        </script>
    </div>
@endsection
