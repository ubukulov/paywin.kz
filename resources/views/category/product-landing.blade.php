@extends('layouts.app')

@section('og_title', $product->name)
@section('og_description', "Купи {$product->name} на Paywin и получи гарантированный приз!")
@section('og_image', $product->mainImage->url ?? asset('img/no-image.png'))

@section('content')
    @php
        $videoUrl = null;
        if ($product->data && is_array($product->data) && !empty($product->data['system_video_url'])) {
            $videoUrl = $product->data['system_video_url'];
        } elseif ($product->meta && is_array($product->meta) && !empty($product->meta['system_video_url'])) {
            $videoUrl = $product->meta['system_video_url'];
        }

        $embedUrl = null;
        $youtubeId = null;
        if ($videoUrl) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/|youtube\.com/shorts/)([^"&?/ ]{11})%i', $videoUrl, $match)) {
                $youtubeId = $match[1];
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
                            <img
                                id="product-main-image"
                                src="{{ $product->mainImage->url ?? ($product->images->first()->url ?? asset('images/no-image.png')) }}"
                                alt="{{ $product->name }}"
                                class="w-full h-full object-contain"
                                loading="lazy"
                            >

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

                            <button type="button" onclick="galleryPrev()"
                                    class="hidden lg:flex items-center justify-center absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 w-10 h-10 rounded-full shadow z-10 font-bold text-xl outline-none hover:bg-white transition">
                                ‹
                            </button>
                            <button type="button" onclick="galleryNext()"
                                    class="hidden lg:flex items-center justify-center absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 w-10 h-10 rounded-full shadow z-10 font-bold text-xl outline-none hover:bg-white transition">
                                ›
                            </button>
                        </div>

                        {{-- Миниатюры --}}
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

                {{-- ПРАВАЯ КОЛОНКА: Информация и форма допуска к Kaspi --}}
                <div class="space-y-6">
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm">
                        <h1 class="text-2xl font-semibold text-gray-900 leading-tight">{{ $product->name }}</h1>

                        <div class="flex items-center gap-3 mt-2">
                            <div class="text-sm text-gray-500">Категория: <span class="font-medium text-gray-800">{{ $product->category->name ?? '—' }}</span></div>
                        </div>

                        {{-- Цены --}}
                        <div class="mt-4 flex items-baseline gap-1.5">
                            <div class="text-3xl font-black text-gray-900">{{ number_format($product->price, 0, '.', ' ') }}</div>
                            <div class="text-xl text-gray-600 font-bold">₸</div>
                            @if($product->old_price ?? false)
                                <div class="text-sm line-through text-gray-400 ml-3 font-medium">{{ number_format($product->old_price, 0, '.', ' ') }} ₸</div>
                            @endif
                        </div>

                        {{-- БЛОК 1: Форма быстрой регистрации для доступа к кнопке Kaspi --}}
                        @guest
                            <div id="landing-auth-box" class="mt-6 p-4 bg-orange-50/70 border border-orange-200 rounded-2xl">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="w-6 h-6 rounded-full bg-orange-500 text-white font-bold text-xs flex items-center justify-center">1</span>
                                    <h3 class="font-bold text-gray-900 text-sm">Укажите данные для получения приза</h3>
                                </div>
                                <p class="text-xs text-gray-600 mb-3">
                                    Введите имя и почту — мы откроем кнопку покупки в Kaspi и закрепим за вами гарантированный бонус.
                                </p>

                                <form id="landing-quick-auth-form" class="space-y-3">
                                    @csrf
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                        <input
                                            type="text"
                                            name="name"
                                            id="landing-user-name"
                                            placeholder="Ваше имя"
                                            required
                                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition"
                                        >
                                        <input
                                            type="email"
                                            name="email"
                                            id="landing-user-email"
                                            placeholder="Ваш Email"
                                            required
                                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition"
                                        >
                                    </div>

                                    <button
                                        type="submit"
                                        id="landing-auth-submit"
                                        class="w-full py-2.5 px-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl transition shadow-xs cursor-pointer"
                                    >
                                        Перейти к покупке в Kaspi
                                    </button>
                                </form>
                                <div id="landing-auth-error" class="mt-2 text-xs text-red-600 hidden"></div>
                            </div>
                        @endguest

                        {{-- БЛОК 2: Контейнер виджета Kaspi (сразу виден для @auth, открывается после формы для @guest) --}}
                        <div id="kaspi-action-wrapper" class="mt-6 {{ Auth::check() ? '' : 'hidden' }}">
                            <div class="p-3.5 bg-gray-50 border border-gray-200 rounded-2xl">
                                <div class="text-xs font-medium text-gray-600 mb-2">
                                    <span class="text-green-600 font-bold">✓ Доступ открыт</span>
                                    <span id="landing-welcome-name">{{ Auth::check() ? '(' . Auth::user()->email . ')' : '' }}</span> — оформите заказ через Kaspi:
                                </div>
                                {{-- Placeholder для динамической кнопки Kaspi --}}
                                <div id="dynamic" class="w-full overflow-hidden"></div>
                            </div>
                        </div>

                        {{-- Блок предзаказа --}}
                        @if($product->is_preorder && $product->delivery_days)
                            <div class="mt-6 flex items-center p-4 bg-amber-50 border border-amber-100 rounded-2xl shadow-xs">
                                <div class="flex-shrink-0 bg-amber-100 p-2 rounded-lg text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-amber-700 font-medium">
                                        Ориентировочная доставка:
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

            @php
                $canLeaveReview = $product->isPurchasedBy(Auth::user());
            @endphp

            @if($canLeaveReview)
                <div class="mt-4">
                    @include('category.block_product_reviews')
                </div>
            @endif
        </div>

        {{-- Скрипты галереи и реферальной ссылки --}}
        <script>
            const productImages = [
                @foreach($product->images as $img)
                    "{{ $img->url }}",
                @endforeach
            ];

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

                if (hasVideo && currentIndex === productImages.length) {
                    if (mainImage) mainImage.classList.add('hidden');
                    if (videoContainer) videoContainer.classList.remove('hidden');
                } else {
                    if (mainImage) {
                        mainImage.classList.remove('hidden');
                        if (productImages[currentIndex]) {
                            mainImage.src = productImages[currentIndex];
                        }
                    }
                    if (videoContainer) {
                        videoContainer.classList.add('hidden');
                        if (videoIframe && videoIframe.contentWindow) {
                            videoIframe.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                        }
                    }
                }

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

            function copyReferralLink() {
                const input = document.getElementById('refLinkInput');
                const message = document.getElementById('copyMessage');
                if (!input) return;

                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(input.value).then(() => {
                        showCopySuccess(message);
                    }).catch(() => fallbackCopy(input, message));
                } else {
                    fallbackCopy(input, message);
                }
            }

            function fallbackCopy(input, message) {
                input.select();
                input.setSelectionRange(0, 99999);
                try {
                    document.execCommand('copy');
                    showCopySuccess(message);
                } catch (err) {
                    console.error('Не удалось скопировать ссылку', err);
                }
            }

            function showCopySuccess(messageElement) {
                if (!messageElement) return;
                messageElement.classList.remove('hidden');
                setTimeout(() => messageElement.classList.add('hidden'), 2500);
            }

            // Функция инициализации виджета Kaspi
            function initKaspiButton() {
                const container = document.getElementById('dynamic');
                if (!container) return;

                container.innerHTML = '<div class="ks-widget" data-template="button" data-merchant-sku="{{ $product->sku }}" data-merchant-code="{{ config('services.kaspi.id') }}" data-city="{{ $currentCity->code }}"></div>';

                if (typeof ksWidgetInitializer !== 'undefined') {
                    ksWidgetInitializer.reinit();
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                selectImage(currentIndex);

                // Если пользователь уже авторизован — запускаем кнопку Kaspi сразу
                @auth
                setTimeout(initKaspiButton, 500);
                @endauth

                // Обработка формы фоновой регистрации для гостей
                const authForm = document.getElementById('landing-quick-auth-form');
                if (authForm) {
                    authForm.addEventListener('submit', async function (e) {
                        e.preventDefault();

                        const submitBtn = document.getElementById('landing-auth-submit');
                        const errorBox = document.getElementById('landing-auth-error');
                        const nameVal = document.getElementById('landing-user-name').value.trim();
                        const emailVal = document.getElementById('landing-user-email').value.trim();

                        errorBox.classList.add('hidden');
                        submitBtn.disabled = true;
                        submitBtn.innerText = 'Проверка...';

                        try {
                            const response = await fetch("{{ route('landing.quick-auth') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    name: nameVal,
                                    email: emailVal
                                })
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                // Обновляем CSRF токен на странице для последующих запросов (например, привязки заказа)
                                if (data.csrf_token) {
                                    document.querySelectorAll('input[name="_token"]').forEach(el => el.value = data.csrf_token);
                                    const metaCsrf = document.querySelector('meta[name="csrf-token"]');
                                    if (metaCsrf) metaCsrf.setAttribute('content', data.csrf_token);
                                }

                                // Скрываем форму и показываем блок с кнопкой Kaspi
                                document.getElementById('landing-auth-box').classList.add('hidden');
                                document.getElementById('landing-welcome-name').innerText = `(${emailVal})`;
                                document.getElementById('kaspi-action-wrapper').classList.remove('hidden');

                                // Инициализируем виджет Kaspi
                                initKaspiButton();

                                if (typeof window.showToast === 'function') {
                                    window.showToast(data.message);
                                }
                            } else {
                                const firstError = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Ошибка регистрации');
                                errorBox.innerText = firstError;
                                errorBox.classList.remove('hidden');
                            }
                        } catch (err) {
                            errorBox.innerText = 'Ошибка соединения с сервером. Попробуйте еще раз.';
                            errorBox.classList.remove('hidden');
                        } finally {
                            submitBtn.disabled = false;
                            submitBtn.innerText = 'Перейти к покупке в Kaspi';
                        }
                    });
                }
            });
        </script>
    </div>
@endsection
