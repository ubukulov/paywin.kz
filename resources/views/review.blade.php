@extends('layouts.app')

@section('content')
    <script src="/js/review.js"></script>
    <script src="https://kit.fontawesome.com/e294a4845f.js"></script>

    <div class="review w-full min-h-screen bg-white pb-24">
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
                            <p class="text-[10px] uppercase font-bold text-gray-400 leading-none mb-1">Текущий рейтинг</p>
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

        {{-- Основная часть --}}
        <div class="px-4">
            <div class="bg-white rounded-[2.5rem] p-6 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-gray-50">
                {{-- Выбор звезд (интерактив обеспечивается JS) --}}
                <div class="flex justify-center gap-3 mb-8 py-2 text-3xl transition-all">
                    <i class="fas fa-star star cursor-pointer text-yellow-400 transition-transform hover:scale-125"></i>
                    <i class="fas fa-star star cursor-pointer text-yellow-400 transition-transform hover:scale-125"></i>
                    <i class="far fa-star star cursor-pointer text-gray-200 transition-transform hover:scale-125"></i>
                    <i class="far fa-star star cursor-pointer text-gray-200 transition-transform hover:scale-125"></i>
                    <i class="far fa-star star cursor-pointer text-gray-200 transition-transform hover:scale-125"></i>
                </div>

                <form action="/" class="space-y-6">
                    @csrf
                    <textarea
                        name="review-textarea"
                        placeholder="Напишите комментарий"
                        class="w-full h-40 p-5 bg-gray-50 rounded-3xl border-none focus:ring-2 focus:ring-[#FD9B11] text-gray-700 placeholder-gray-400 resize-none transition-all"
                    ></textarea>

                    {{-- Оранжевая кнопка из вашего стиля --}}
                    <button type="submit"
                            class="review__form-btn w-full flex items-center justify-center gap-3 bg-[#FD9B11] text-white font-bold text-base py-4 px-8 rounded-full shadow-[0_0_23px_#fd9b11] hover:brightness-110 active:scale-[0.98] transition-all uppercase tracking-wider">
                        отправить
                        <img src="/images/right-arrow.svg" alt="arrow" class="w-4 h-4 brightness-0 invert">
                    </button>
                </form>
            </div>
        </div>
    </div>
@stop
