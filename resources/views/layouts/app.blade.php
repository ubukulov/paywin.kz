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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Скрипты --}}
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/home.js') }}"></script>

    {{-- Внешние библиотеки (Tailwind и Alpine с фиксом defer) --}}
    <script src="{{ asset('css/tailwindcss.css') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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

<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 h-24 flex items-center justify-between">

        <div class="flex items-center gap-8">
            <a href="{{ route('home') }}" class="block transform transition hover:scale-105">
                <img src="{{ asset('images/logo_bg_white.jpg') }}" alt="Paywin" class="h-16 w-auto object-contain">
            </a>

            <div x-data="{
    open: false,
    selectedName: '{{ $currentCity->name ?? 'Выберите город' }}',
    async selectCity(id, name) {
        this.selectedName = name;
        this.open = false;

        try {
            await axios.post('{{ route('city.set') }}', { city_id: id, _token: '{{ csrf_token() }}' });
            // Перезагружаем страницу, чтобы все цены и остатки обновились под выбранный город
            window.location.reload();
        } catch (error) {
            window.showToast('Ошибка при выборе города');
        }
    }
}" class="relative hidden lg:block">

                <button @click="open = !open" class="group flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">
                    <div class="p-2 bg-gray-50 rounded-lg group-hover:bg-indigo-50 transition-colors">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                    </div>
                    <span x-text="selectedName"></span>
                    <svg class="w-3 h-3 opacity-50 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-cloak
                     class="absolute top-full left-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 py-3 z-[60]">
                    @foreach($cities as $city)
                        <button @click="selectCity({{ $city->id }}, '{{ $city->name }}')"
                                class="w-full text-left px-5 py-2.5 text-sm font-medium hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ ($currentCity && $currentCity->id == $city->id) ? 'text-indigo-600 bg-indigo-50/50' : '' }}">
                            {{ $city->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">

            <button id="scanQR" class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-orange-200 transition active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="hidden md:inline">Сканировать QR</span>
            </button>

            <a href="{{ route('cart.index') }}" class="relative p-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition group">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">
                        {{ $cartCount ?? 0 }}
                    </span>
            </a>

            {{-- Если нужно добавить Профиль --}}
            <a @if(\Illuminate\Support\Facades\Auth::user()->user_type == 'user') href="{{ route('user.cabinet') }}" @else href="{{ route('partner.cabinet') }}"  @endif class="p-2.5 text-gray-400 hover:text-indigo-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </a>
        </div>
    </div>

    <div class="camera--hidden camera-container overflow-hidden bg-gray-900 h-0" id="camera-panel">
        <div class="max-w-xl mx-auto py-8 px-4 text-center">
            <div id="video-container" class="rounded-3xl overflow-hidden shadow-2xl border-4 border-white/10 aspect-video relative">
                <video id="qr-video" class="w-full h-full object-cover"></video>
            </div>
            <div class="mt-6 flex justify-center">
                <a href="{{ route('howItWorks') }}" class="inline-flex items-center gap-2 text-white/60 hover:text-white transition text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Как это работает?
                </a>
            </div>
        </div>
    </div>
</header>

    {{-- Контент страницы --}}
    <div id="content">
        @yield('content')
    </div>

    {{-- Подключаемые шаблоны --}}
    {{--@include('_partials.bottom_menu')--}}
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
