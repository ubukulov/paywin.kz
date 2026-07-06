@extends('user.user')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">

        {{-- Заголовок страницы --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight flex items-center gap-2">
                    <span>📦</span> Мои купленные товары
                </h1>
                <p class="text-xs text-gray-400 mt-1">
                    История ваших оплаченных покупок и предзаказов на платформе.
                </p>
            </div>
            <a href="/" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-4 py-2 rounded-xl transition">
                Назад на витрину →
            </a>
        </div>

        @if($purchasedItems->isEmpty())
            {{-- Фолбек, если покупок еще нет --}}
            <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-xs">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                    🛍️
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-1">Вы еще ничего не купили</h3>
                <p class="text-xs text-gray-400 max-w-xs mx-auto mb-6">
                    Все оплаченные вами товары от партнеров будут мгновенно отображаться на этой странице.
                </p>
                <a href="/" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-3 px-6 rounded-2xl transition shadow-sm">
                    Перейти к покупкам
                </a>
            </div>
        @else
            {{-- Таблица / Список карточек товаров --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($purchasedItems as $item)
                    @php
                        $product = $item->product;
                        $order = $item->order;
                        $isPreorder = $order->status === \App\Enums\OrderEnum::PREORDER->value;

                        // Определяем дату доставки для предзаказа (из данных заказа или из самого товара)
                        $deliveryDate = null;
                        if ($isPreorder) {
                            if (!empty($order->data['delivery_at'])) {
                                $deliveryDate = \Carbon\Carbon::parse($order->data['delivery_at']);
                            } elseif (!empty($product->preorder_delivery_date)) {
                                $deliveryDate = \Carbon\Carbon::parse($product->preorder_delivery_date);
                            }
                        }
                    @endphp

                    <div class="bg-white rounded-2xl border border-gray-100 p-4 flex flex-col justify-between hover:shadow-md transition duration-200 relative overflow-hidden group">

                        {{-- Основной блок контента --}}
                        <div class="flex gap-4">
                            {{-- Изображение товара --}}
                            <div class="w-24 h-24 rounded-xl bg-gray-50 border border-gray-100 shrink-0 overflow-hidden relative">
                                <img
                                    src="{{ $product->mainImage->url ?? asset('images/no-image.png') }}"
                                    alt="{{ $product->name ?? 'Товар' }}"
                                    class="w-full h-full object-contain group-hover:scale-105 transition duration-300"
                                >
                            </div>

                            {{-- Информация о покупке --}}
                            <div class="flex flex-col justify-between flex-1 min-w-0">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                            Заказ №{{ $item->order_id }}
                                        </span>

                                        {{-- Бейдж статуса --}}
                                        @if($isPreorder)
                                            <span class="text-[9px] font-black uppercase tracking-wider bg-amber-50 text-amber-700 px-2 py-0.5 rounded-md border border-amber-100 animate-pulse">
                                                ⏳ Предзаказ
                                            </span>
                                        @else
                                            <span class="text-[9px] font-black uppercase tracking-wider bg-green-50 text-green-700 px-2 py-0.5 rounded-md border border-green-100">
                                                ✅ Оплачено
                                              </span>
                                        @endif
                                    </div>

                                    <h3 class="text-sm font-bold text-gray-900 truncate pr-4">
                                        {{ $product ? $product->name : 'Товар удален или недоступен' }}
                                    </h3>

                                    <p class="text-xs text-gray-500 mt-1 font-medium">
                                        Количество: <span class="text-gray-900 font-bold">{{ $item->quantity }} шт.</span>
                                        • на сумму <span class="text-gray-900 font-bold">{{ number_format($item->total, 0, '.', ' ') }} ₸</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- БЛОК ДОСТАВКИ ДЛЯ ПРЕДЗАКАЗА --}}
                        @if($isPreorder)
                            <div class="mt-3 bg-amber-50/60 border border-amber-100/70 rounded-xl p-2.5 flex items-center gap-2.5">
                                <div class="text-lg">🚚</div>
                                <div class="text-left">
                                    <p class="text-[10px] font-bold uppercase text-amber-800 tracking-tight">Ожидаемая дата доставки:</p>
                                    <p class="text-xs font-extrabold text-gray-900 mt-0.5">
                                        @if($deliveryDate)
                                            {{ $deliveryDate->translatedFormat('d F Y') }} г. в {{ $deliveryDate->format('H:i') }}
                                        @else
                                            Уточняется менеджером
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- Подвал карточки: дата покупки и кнопка перехода --}}
                        <div class="flex items-center justify-between pt-2 border-t border-gray-50 text-[11px] mt-3">
                            <span class="text-gray-400 font-medium">
                                Куплено: {{ $item->created_at->format('d.m.Y в H:i') }}
                            </span>

                            @if($product)
                                <a href="{{ route('product.show', ['slug' => $product->slug]) }}" class="text-indigo-600 hover:text-indigo-700 font-bold flex items-center gap-0.5 transition">
                                    Страница товара <span>→</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Пагинация --}}
            <div class="mt-8">
                {{ $purchasedItems->links() }}
            </div>
        @endif

    </div>
@endsection
