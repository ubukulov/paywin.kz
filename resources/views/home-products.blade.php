@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-4 pb-12">
    <!-- фильтры / поиск -->
    {{--<div class="flex flex-col sm:flex-row gap-3 items-center justify-between mb-6">
        <div class="flex gap-3 w-full sm:w-auto">
            <input type="text" placeholder="Поиск по названию"
                   class="w-full sm:w-80 px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" />
            <select class="px-3 py-2 border rounded-md">
                <option value="popular">Популярные</option>
                <option value="price_asc">Цена ↑</option>
                <option value="price_desc">Цена ↓</option>
            </select>
        </div>
        <div class="text-sm text-gray-600">Показано <span class="font-medium">12</span> товаров</div>
    </div>--}}

    @include('_partials._products')
</main>
@stop
