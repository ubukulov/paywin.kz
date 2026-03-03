@extends('layouts.app')

@section('content')
    {{-- Подключение скриптов остается без изменений --}}
    <script src="/js/main.min.js"></script>
    <script src="https://kit.fontawesome.com/e294a4845f.js"></script>

    <div class="not-given-prize w-full min-h-screen bg-white pb-12">

        {{-- Заголовок страницы --}}
        <div class="px-4 py-6">
            <h1 class="text-sm font-black tracking-[3px] text-gray-400 uppercase flex items-center gap-2 animate__animated animate__fadeInLeft">
                <span class="w-8 h-[2px] bg-[#FD9B11]"></span>
                Оставить отзыв
            </h1>
        </div>

        {{-- Шапка с инфо о заведении --}}
        <div class="px-4 mb-8">
            <div class="flex items-center gap-4 p-4 rounded-3xl bg-gray-50 border border-gray-100 shadow-sm">
                <div class="w-20 h-20 flex-shrink-0 rounded-2xl overflow-hidden shadow-md">
                    <img src="/images/review/Bitmap.png" alt="logo" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-black text-gray-900 leading-tight">KFC</h2>
                    <div class="flex items-end justify-between mt-1">
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 leading-none mb-1 text-nowrap">Текущий рейтинг</p>
                            <div class="flex text-yellow-400 text-xs gap-0.5">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star text-gray-200"></i>
                                <i class="fas fa-star text-gray-200"></i>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-gray-800 leading-none">3.2</div>
                    </div>
                    <a href="#" class="text-[11px] text-[#FD9B11] font-bold border-b border-dashed border-[#FD9B11] mt-2 inline-block">
                        на основе 443 отзывов
                    </a>
                </div>
            </div>
        </div>

        {{-- Основной блок обратной связи --}}
        <div class="px-4">
            <div class="bg-white rounded-[2.5rem] p-6 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50">

                <p class="text-base font-bold text-gray-800 leading-snug mb-6 text-center animate__animated animate__fadeIn">
                    Напишите пожалуйста, по какой причине вы не получили
                    приз от партнера, и мы постараемся максимально быстро
                    решить ваш вопрос.
                </p>

                <form action="/" class="space-y-6">
                    @csrf
                    <textarea
                        name="review-textarea"
                        placeholder="Напишите комментарий"
                        class="w-full h-40 p-5 bg-gray-50 rounded-3xl border-none focus:ring-2 focus:ring-[#FD9B11] text-gray-700 placeholder-gray-400 resize-none transition-all"
                    ></textarea>

                    <button type="submit"
                            class="w-full flex items-center justify-center gap-3 bg-[#FD9B11] text-white font-bold text-base py-4 px-8 rounded-full shadow-[0_0_23px_#fd9b11] hover:brightness-110 active:scale-[0.98] transition-all uppercase tracking-wider">
                        продолжить
                        <img src="/images/right-arrow.svg" alt="icon" class="w-4 h-4 brightness-0 invert">
                    </button>
                </form>

                {{-- Блок контактов --}}
                <div class="mt-10 text-center">
                    <p class="text-sm font-semibold text-gray-400 leading-relaxed mb-6">
                        или свяжитесь с нами любым удобным способом <br>
                        <span class="text-gray-900 font-black text-lg">+7 (775) 993-11-57</span>
                    </p>

                    {{-- Социальные ссылки --}}
                    <div class="flex items-center justify-center gap-6">
                        <a href="https://t.me/+77759931157"
                           class="w-14 h-14 flex items-center justify-center bg-sky-50 rounded-2xl hover:scale-110 transition-transform shadow-sm">
                            <img src="/images/telegram.svg" alt="telegram" class="w-7 h-7">
                        </a>
                        <a href="tel:+77759931157"
                           class="w-14 h-14 flex items-center justify-center bg-green-50 rounded-2xl hover:scale-110 transition-transform shadow-sm">
                            <img src="/images/phone.svg" alt="phone" class="w-7 h-7">
                        </a>
                        <a href="https://wa.me/+77759931157"
                           class="w-14 h-14 flex items-center justify-center bg-emerald-50 rounded-2xl hover:scale-110 transition-transform shadow-sm">
                            <img src="/images/whatsApp.svg" alt="whatsapp" class="w-7 h-7">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
