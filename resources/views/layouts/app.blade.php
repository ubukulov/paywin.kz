<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Главная</title>

    {{-- Стили --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fix.css') }}">

    {{-- Иконки и мета-данные --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicons/site.webmanifest') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    {{-- Скрипты --}}
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>

    {{-- Внешние библиотеки (Tailwind и Alpine с фиксом defer) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        /* Стили для корректной работы Alpine.js (скрывает элементы до загрузки) */
        [x-cloak] { display: none !important; }

        div { margin-bottom: 0px; }
        .header-line { display: flex; justify-content: center; gap: 50px; }
        .cart {
            padding: 0 10px;
            margin-top: 25px;
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0px 2px 8px 0px rgba(0,0,0,0.18);
        }
        #video-container { line-height: 0; max-width: 100%; }
        #video-container.example-style-1 .scan-region-highlight-svg,
        #video-container.example-style-1 .code-outline-highlight {
            stroke: #64a2f3 !important;
        }
        #video-container.example-style-2 {
            position: relative;
            width: max-content;
            height: max-content;
            overflow: hidden;
        }
        #video-container.example-style-2 .scan-region-highlight {
            border-radius: 30px;
            outline: rgba(0, 0, 0, .25) solid 50vmax;
        }
        #video-container.example-style-2 .scan-region-highlight-svg { display: none; }
        #video-container.example-style-2 .code-outline-highlight {
            stroke: rgba(255, 255, 255, .5) !important;
            stroke-width: 15 !important;
            stroke-dasharray: none !important;
        }
        #flash-toggle { display: none; }
        hr { margin-top: 32px; }
        input[type="file"] { display: block; margin-bottom: 16px; }
    </style>
</head>
<body class="home-page">

<section class="camera">
    <div class="container">
        <div style="display: flex;">
            <div style="flex: 1;">
                <img class="logo" src="{{ asset('images/logo_bg_black.jpg') }}" alt="Logo">
            </div>
            <div style="display: block; align-content: end;">
                <button id="scanQR" class="button camera__button">Сканировать QR</button>
            </div>
        </div>

        <div class="camera--hidden animate__animated animate__fadeInDown" style="text-align: center;">
            <div id="video-container" style="width: 100% !important;">
                <video id="qr-video" style="max-width: 100%; height: 200px; transform: scaleX(1) !important;"></video>
            </div>
            <a href="{{ route('howItWorks') }}" class="how-it-works animate__animated animate__fadeIn">
                <img src="/img/icons/how-it-works.svg" alt="info" class="how-it-works__img">
                <p class="how-it-works__text">Как это работает?</p>
            </a>
        </div>

        <div class="header-line">
            <div>
                <form action="" class="home-page__form animate__animated animate__fadeIn">
                    <input type="text" name="location" placeholder="Алматы" class="home-page__select">
                    <input type="text" placeholder="Искать акцию или компанию.." class="home-page__input">
                    <button type="submit" class="home-page__search"><img src="/img/icons/search.svg" alt="Найти"></button>
                </form>
            </div>

            @if(Route::currentRouteName() !== 'cart.index')
                <div class="cart">
                    <a href="{{ route('cart.index') }}" class="relative inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M2.25 2.25h1.386c.51 0 .957.343 1.087.835L5.25 6.75m0 0h13.5l-1.5 9h-12l-1.5-9zm0 0L4.723 4.085A1.125 1.125 0 0 0 3.636 3.25H2.25M9 21a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm9 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0z"/>
                        </svg>
                        <span id="cart-count"
                              class="absolute -top-1 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">
                            {{ $cartCount ?? 0 }}
                        </span>
                    </a>
                </div>
            @endif
        </div>

        {{-- Скрытые настройки QR-сканера (необходимы для JS-логики) --}}
        <div style="display: none;">
            <select id="scan-region-highlight-style-select"><option value="default-style">Default</option></select>
            <input id="show-scan-region" type="checkbox">
            <select id="inversion-mode-select"><option value="original">Original</option></select>
            <span id="cam-has-camera"></span>
            <select id="cam-list"><option value="environment" selected>Environment</option></select>
            <span id="cam-has-flash"></span>
            <button id="flash-toggle"><span id="flash-state">off</span></button>
            <span id="cam-qr-result">None</span>
            <span id="cam-qr-result-timestamp"></span>
            <button id="start-button">Start</button>
            <button id="stop-button">Stop</button>
            <input type="file" id="file-selector">
            <span id="file-qr-result">None</span>
        </div>

        {{-- Логика QR сканера --}}
        <script type="module">
            import QrScanner from "/js/qr-scanner.min.js";

            const video = document.getElementById('qr-video');
            const videoContainer = document.getElementById('video-container');
            const camHasCamera = document.getElementById('cam-has-camera');
            const camList = document.getElementById('cam-list');
            const camHasFlash = document.getElementById('cam-has-flash');
            const flashToggle = document.getElementById('flash-toggle');
            const flashState = document.getElementById('flash-state');
            const camQrResult = document.getElementById('cam-qr-result');
            const camQrResultTimestamp = document.getElementById('cam-qr-result-timestamp');
            const fileSelector = document.getElementById('file-selector');
            const fileQrResult = document.getElementById('file-qr-result');

            function setResult(label, result) {
                console.log(result.data);
                label.textContent = result.data;
                camQrResultTimestamp.textContent = new Date().toString();
                label.style.color = 'teal';
                window.location.href = result.data;
                scanner.stop();
            }

            const scanner = new QrScanner(video, result => setResult(camQrResult, result), {
                onDecodeError: error => {
                    camQrResult.textContent = error;
                },
                highlightScanRegion: true,
                highlightCodeOutline: true,
            });

            scanner.stop();

            QrScanner.hasCamera().then(hasCamera => camHasCamera.textContent = hasCamera);

            document.getElementById('scanQR').addEventListener('click', () => {
                scanner.start();
            });

            document.getElementById('stop-button').addEventListener('click', () => {
                scanner.stop();
            });

            fileSelector.addEventListener('change', event => {
                const file = fileSelector.files[0];
                if (!file) return;
                QrScanner.scanImage(file, { returnDetailedScanResult: true })
                    .then(result => setResult(fileQrResult, result))
                    .catch(e => setResult(fileQrResult, e || 'No QR code found.'));
            });
        </script>
    </div>
</section>

{{-- Контент страницы --}}
@yield('content')

{{-- Подключаемые шаблоны --}}
@include('_partials.bottom_menu')
@include('_partials.info')

{{-- НОВОЕ: Универсальное уведомление (Toast) --}}
<div
    x-data="{ show: false, message: '' }"
    x-on:show-toast.window="message = $event.detail; show = true; setTimeout(() => show = false, 3000)"
    x-show="show"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    class="fixed bottom-24 right-6 z-[9999] bg-green-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
    </svg>
    <span x-text="message" class="font-medium"></span>
</div>

<script>
    // Глобальная функция для вызова уведомления
    window.showToast = function(msg) {
        window.dispatchEvent(new CustomEvent('show-toast', { detail: msg }));
    };
</script>

</body>
</html>
