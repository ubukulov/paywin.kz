@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="py-6 space-y-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-cyan-600 to-blue-700 p-8 rounded-3xl shadow-xl relative overflow-hidden">
            <div class="absolute -left-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <h1 class="text-3xl md:text-4xl font-black text-white leading-tight tracking-tight uppercase">
                    Создайте склад <br> <span class="text-cyan-100">для продаж</span>
                </h1>
            </div>

            <a href="{{ route('partner.warehouse.create') }}"
               class="relative z-10 inline-flex items-center justify-center px-8 py-4 bg-white text-cyan-700 font-bold rounded-2xl shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all active:scale-95 whitespace-nowrap">
                <span class="mr-2 text-2xl">+</span> новый склад
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-4 text-[10px] uppercase font-black text-gray-400 tracking-widest">Город</th>
                        <th class="px-6 py-4 text-[10px] uppercase font-black text-gray-400 tracking-widest">Наименование</th>
                        <th class="px-6 py-4 text-[10px] uppercase font-black text-gray-400 tracking-widest">Адрес</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                    @forelse($warehouses as $warehouse)
                        <tr class="hover:bg-cyan-50/30 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-cyan-400"></div>
                                    <span class="text-sm font-semibold text-gray-600">{{ $warehouse->city->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                    <span class="text-sm font-bold text-gray-900 group-hover:text-cyan-700 transition-colors">
                                        {{ $warehouse->name }}
                                    </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                    <i class="fas fa-map-marker-alt text-gray-300 group-hover:text-cyan-400 transition-colors"></i>
                                    {{ $warehouse->address }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-store-slash text-gray-300 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-400 font-medium">Магазины еще не добавлены</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    {{-- Скрипты оставляем, если они нужны для других частей лейаута --}}
    <script src="/js/my-promo-promo.js"></script>
    <script src="/js/about-partner.js"></script>
@endpush
