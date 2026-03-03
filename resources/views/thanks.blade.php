@extends('layouts.app')

@section('content')
    <div class="w-full min-h-screen bg-white px-4 py-8 pb-32 flex flex-col items-center text-center">

        {{-- Заголовок --}}
        <h1 class="text-3xl font-black text-gray-900 mb-6 animate__animated animate__fadeInLeft">Поздравляем!</h1>

        {{-- Анимированная зеленая галочка --}}
        <div class="mb-6 animate__animated animate__zoomIn">
            <svg class="w-24 h-24 mx-auto" viewBox="0 0 89 89" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="44.5" cy="44.5" r="41.5" stroke="#17D200" stroke-width="6"></circle>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M64.6301 27.8881C63.2252 26.9616 61.3277 27.3464 60.3931 28.7332L40.3121 58.6576L27.7127 49.2543C26.2882 48.354 24.4002 48.7638 23.4844 50.1606C22.5734 51.5676 22.9831 53.4348 24.4029 54.3357L39.539 65.3546C39.7873 65.5107 40.0517 65.6264 40.3215 65.7052C40.3229 65.7052 40.3242 65.7065 40.3249 65.7065C40.4527 65.7435 40.5826 65.7697 40.7125 65.7906C40.7273 65.792 40.7421 65.7973 40.7569 65.8C40.9009 65.8195 41.0455 65.8303 41.1895 65.8303C41.3147 65.8303 41.4378 65.8222 41.5616 65.8081C41.6599 65.796 41.7581 65.7758 41.855 65.7549C41.8785 65.7496 41.9021 65.7475 41.925 65.7422C42.2829 65.6554 42.6247 65.504 42.9322 65.294C42.9343 65.2927 42.9363 65.2913 42.9376 65.29C43.0258 65.2308 43.1092 65.1635 43.1913 65.0928C43.2061 65.0787 43.2229 65.0693 43.2377 65.0558C43.3023 64.9973 43.3615 64.934 43.4201 64.8708C43.4504 64.8392 43.4826 64.8102 43.5109 64.7773C43.5533 64.7288 43.591 64.675 43.6314 64.6232C43.6684 64.5747 43.7074 64.529 43.7424 64.4778L65.488 32.0733C66.424 30.6812 66.0391 28.8093 64.6301 27.8881Z" fill="#17D200"></path>
            </svg>
        </div>

        {{-- Инфо об оплате --}}
        <div class="space-y-1 mb-8">
            <p class="text-sm font-black text-green-500 uppercase tracking-widest animate__animated animate__fadeInUp">успешно оплачено</p>
            <p class="text-5xl font-black text-gray-900 animate__animated animate__fadeInUp">{{ number_format($payment->amount, 0, '.', ' ') }}₸</p>
            <p class="text-gray-400 text-sm animate__animated animate__fadeIn">{{ date('d.m.Y H:i', strtotime($payment->updated_at)) }}</p>
        </div>

        {{-- Блок приза (Золотая карточка) --}}
        @if($share)
            <div class="w-full max-w-sm mb-8 animate__animated animate__fadeIn">
                <h2 class="text-xs font-black text-orange-400 uppercase tracking-widest mb-3">Ваш выигрыш</h2>
                <div class="bg-gradient-to-br from-orange-400 to-yellow-500 p-6 rounded-[2.5rem] shadow-xl shadow-orange-200 relative overflow-hidden group">
                    {{-- Декоративный круг на фоне --}}
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/20 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>

                    <p class="text-white text-xl font-bold leading-tight relative z-10">
                        {{ $share->title }}<br>
                        <span class="text-white/80 text-sm font-medium">до {{ number_format($share->to_order, 0, '.', ' ') }}₸</span>
                    </p>
                </div>
            </div>
        @endif

        {{-- Инструкция --}}
        <p class="text-gray-500 font-medium mb-10 px-6">Покажите этот экран партнёру для получения приза</p>

        {{-- Секция подтверждения и кнопок --}}
        <div id="dis_b" class="w-full space-y-4 animate__animated animate__fadeInUp">
            <h2 class="text-lg font-bold text-gray-800">Вы получили приз?</h2>

            <div class="flex items-center gap-4 w-full px-2">
                <button onclick="clickBtn()"
                        class="flex-1 py-4 bg-[#18BE1E] text-white font-black rounded-3xl shadow-lg shadow-green-100 hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-wider">
                    да
                </button>
                <a href="{{ route('notGivenPrize', ['id' => $payment->partner_id]) }}"
                   class="flex-1 py-4 bg-gray-100 text-gray-500 font-bold rounded-3xl hover:bg-gray-200 active:scale-95 transition-all uppercase tracking-wider text-center">
                    нет
                </a>
            </div>

            <div class="pt-4">
                <a href="{{ route('review') }}"
                   class="inline-block w-full py-4 text-gray-400 font-bold border-2 border-dashed border-gray-200 rounded-3xl hover:border-orange-400 hover:text-orange-400 transition-all uppercase tracking-wider text-sm">
                    оставить оценку и отзыв
                </a>
            </div>
        </div>

        {{-- Финальное сообщение после подтверждения --}}
        <div id="suc" class="hidden w-full px-4 animate__animated animate__zoomIn">
            <div class="bg-green-50 p-6 rounded-[2rem] border border-green-100">
                <p class="text-green-800 font-bold leading-relaxed">
                    Благодарим за покупку! <br>
                    Надеемся, Вам всё понравилось, и ждём Вас снова ✨
                </p>
                <a href="{{ route('home') }}" class="mt-4 inline-block text-[#18BE1E] font-black uppercase text-sm border-b-2 border-[#18BE1E]">Вернуться на главную</a>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        function clickBtn(){
            const dis_b = document.getElementById('dis_b');
            const suc = document.getElementById('suc');

            dis_b.classList.add('hidden');
            suc.classList.remove('hidden');
            suc.classList.add('block');
        }
    </script>
@stop
