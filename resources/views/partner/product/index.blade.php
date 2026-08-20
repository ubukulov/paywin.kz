@extends('partner.partner')

@push('partner_styles')
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="py-6 space-y-8 max-w-7xl mx-auto px-4">

        {{-- Шапка --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-indigo-700 to-purple-800 p-8 rounded-3xl shadow-xl overflow-hidden relative">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <h1 class="text-3xl md:text-4xl font-black text-white leading-tight tracking-tight uppercase">
                    Создавайте <br> <span class="text-indigo-200">крутые товары</span>
                </h1>
            </div>

            <a href="{{ route('partner.product.create') }}"
               class="relative z-10 inline-flex items-center justify-center px-6 py-3 bg-white text-indigo-700 font-bold rounded-xl shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all active:scale-95 whitespace-nowrap">
                <span class="mr-2 text-xl">+</span> новый товар
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Сетка товаров (Grid по 50 штук) --}}
        @if($products->isEmpty())
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-xs">
                <div class="text-4xl mb-3">📦</div>
                <h3 class="text-sm font-bold text-gray-900">У вас пока нет товаров</h3>
                <p class="text-xs text-gray-400 max-w-sm mx-auto mt-1">Добавьте свой первый товар, чтобы он появился в каталоге.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach($products as $product)
                    <article class="group bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden hover:shadow-xl hover:border-indigo-100 transition-all duration-300 flex flex-col relative">

                        {{-- Изображение --}}
                        <div class="relative overflow-hidden aspect-square bg-gray-50">
                            <img src="{{ $product->mainImage->url ?? asset('images/no-image.png') }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy" />
                        </div>

                        {{-- Инфо --}}
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="text-xs font-bold text-gray-800 line-clamp-2 min-h-[32px] mb-2 leading-snug">
                                {{ $product->name }}
                            </h3>

                            <div class="mt-auto">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-400 font-medium uppercase italic">Цена:</span>
                                        <div class="flex items-baseline gap-0.5">
                                            <span class="text-base font-black text-gray-900">{{ number_format($product->price, 0, '.', ' ') }}</span>
                                            <span class="text-xs font-bold text-gray-500">₸</span>
                                        </div>
                                    </div>

                                    {{-- Кнопки действий: Редактировать и Удалить --}}
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('partner.product.edit', $product->id) }}"
                                           title="Редактировать"
                                           class="flex items-center justify-center w-8 h-8 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-2xs">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>

                                        <form action="{{ route('partner.product.destroy', $product->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Вы уверены, что хотите удалить товар «{{ addslashes($product->name) }}»?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    title="Удалить"
                                                    class="flex items-center justify-center w-8 h-8 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-600 hover:text-white transition-all shadow-2xs">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-2.5 border-t border-gray-100 text-[10px]">
                                    <div class="text-gray-500 italic">Склад: <span class="font-bold text-gray-800 not-italic">{{ $product->quantity }} шт</span></div>
                                    <div class="text-gray-400 font-mono">#{{ $product->sku }}</div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Пагинация на 50 товаров --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@stop
