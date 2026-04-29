<section class="camera">
    <div class="">
        <div style="display: flex;">
            <div style="flex: 1;">
                <a href="{{ route('home') }}">
                    <img class="logo" src="{{ asset('images/logo_bg_black.jpg') }}" alt="Logo">
                </a>
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
