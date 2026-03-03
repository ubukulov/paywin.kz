@extends('layouts.app')

@section('content')
    <div class="w-full min-h-screen bg-white px-4 py-8 pb-24">
        <div class="max-w-xl mx-auto">

            {{-- Заголовок --}}
            <div class="mb-8 animate__animated animate__fadeInLeft">
                <h1 class="text-3xl font-black text-gray-900 leading-tight">
                    Paywin.kz — <br>
                    <span class="text-[#18BE1E]">призы за покупки!</span>
                </h1>
                <div class="w-12 h-1.5 bg-[#FD9B11] rounded-full mt-4"></div>
            </div>

            {{-- Контентная часть --}}
            <div class="space-y-8">

                {{-- О нас --}}
                <section class="animate__animated animate__fadeInUp">
                    <h2 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#18BE1E]"></span>
                        О нас
                    </h2>
                    <p class="text-gray-700 leading-relaxed font-medium">
                        Дарим покупателям подарки и положительные эмоции за каждую покупку среди десятка наших партнеров, выстраивая отношения по стратегии <span class="text-[#18BE1E] font-bold">WIN WIN!</span>
                    </p>
                </section>

                {{-- Как это работает --}}
                <section class="bg-gray-50 p-6 rounded-[2rem] border border-gray-100 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                    <h2 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-3">Как это работает?</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Выберите любого из наших партнеров, совершите покупку от определенной суммы, оплатив через <span class="font-bold text-gray-800 text-nowrap">QR</span>, и выиграйте моментально один из десятка ценных призов.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach(['Кофе', 'Пицца', 'SPA', 'Скидки', 'Кэшбэк 100%'] as $item)
                            <span class="text-[10px] font-bold bg-white text-gray-500 px-3 py-1 rounded-full border border-gray-100 shadow-sm">
                                {{ $item }}
                            </span>
                        @endforeach
                    </div>
                </section>

                {{-- Для партнеров --}}
                <section class="animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <h2 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#FD9B11]"></span>
                        Для партнеров
                    </h2>
                    <p class="text-gray-700 leading-relaxed font-medium">
                        Наполняем каждую покупку положительными эмоциями с целью повышения среднего чека, уровня лояльности и LTV клиента.
                    </p>
                </section>

                {{-- Контакты / Призыв к действию --}}
                <div class="pt-6 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                    <a href="https://wa.me/77759931157"
                       class="flex flex-col items-center justify-center gap-2 p-6 bg-emerald-50 rounded-[2rem] border border-emerald-100 transition-transform active:scale-[0.98] group">
                        <p class="text-xs font-bold text-emerald-600 uppercase tracking-tighter">По вопросам подключения:</p>
                        <div class="flex items-center gap-3">
                            <img src="/images/whatsApp.svg" alt="whatsapp" class="w-6 h-6">
                            <span class="text-xl font-black text-emerald-700">8 (775) 993-11-57</span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
@stop
