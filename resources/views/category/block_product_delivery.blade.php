{{-- Короткие карточки доп.информации (опционально) --}}
<div
    class="mt-5 p-5 bg-[#fff7ed] border border-orange-200/60 rounded-[2.25rem] shadow-sm relative group/main">
    {{--<div class="bg-white p-4 rounded-xl shadow-sm">
        <h4 class="text-xs text-gray-500">Быстрая информация</h4>
        <div class="mt-2 text-sm text-gray-700">
            <div>Артикул: <span class="font-mono">{{ $product->sku }}</span></div>
            <div>В наличии: <span class="font-medium">{{ $product->quantity }}</span></div>
        </div>
    </div>--}}

    <div class="bg-white p-4 rounded-xl shadow-sm">
        <h4 class="text-xs text-gray-500">Доставка</h4>
        <div class="mt-2 text-sm text-gray-700">
            Доставка по @if($currentCity->name== "Алматы")
                @if($product->is_preorder)
                    Алматы 15 дней.
                @else
                    Алматы 1–2 дня.
                @endif
            @else
                Казахстану 3-5 дней.
            @endif Бесплатная доставка от 50 000 ₸.
        </div>
        <br>
        {{--<h4 class="text-xs text-gray-500">Быстрая информация</h4>
        <div class="mt-2 text-sm text-gray-700">
            <div>Артикул: <span class="font-mono">{{ $product->sku }}</span></div>
            <div>В наличии: <span class="font-medium">{{ $product->quantity }}</span></div>
        </div>--}}
    </div>
</div>
