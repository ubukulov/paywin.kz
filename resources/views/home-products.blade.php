@extends('layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 pb-12 mt-6">

        {{-- Главный контейнер: Категории слева, Товары справа --}}
        <div class="flex flex-col lg:flex-row gap-6 items-start">

            {{-- ЛЕВАЯ КОЛОНКА: Сайдбар с категориями (Фиксированная ширина w-72 на десктопе) --}}
            <aside class="w-full lg:w-72 shrink-0 bg-white p-4 rounded-2xl border border-gray-100 shadow-xs">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-3 px-2">
                    Категории
                </h3>

                {{-- Список категорий в стиле Kaspi --}}
                <nav class="space-y-2" id="categories-tree">
                    @if(isset($categories) && $categories->isNotEmpty())
                        {{-- Берем только корневых родителей (parent_id == 0) --}}
                        @foreach($categories->where('parent_id', 0) as $rootCat)
                            @php
                                $children = $categories->where('parent_id', $rootCat->id);
                                $hasChildren = $children->isNotEmpty();

                                // Проверяем, активен ли этот родитель или кто-то из его детей,
                                // чтобы дерево было сразу раскрыто при переходе на эту страницу
                                $isCurrentRootActive = isset($category) && $category->id === $rootCat->id;
                                $isChildActive = isset($category) && $children->contains('id', $category->id);
                                $isOpen = $isCurrentRootActive || $isChildActive;
                            @endphp

                            <div class="category-item-group border-b border-gray-50 pb-2 last:border-0" data-category-id="{{ $rootCat->id }}">

                                {{-- Строка родительской категории --}}
                                <div class="flex items-center justify-between group/row rounded-xl px-2 py-1.5 transition duration-150 {{ $isCurrentRootActive ? 'bg-orange-50' : 'hover:bg-gray-50' }}">

                                    {{-- Левая часть: Стрелка + Название --}}
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        @if($hasChildren)
                                            {{-- Круглая кнопка-стрелка для раскрытия меню --}}
                                            <button type="button"
                                                    onclick="toggleCategoryTree(event, {{ $rootCat->id }})"
                                                    class="toggle-btn w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-orange-500 hover:text-white transition-all duration-200 shrink-0 focus:outline-none {{ $isOpen ? 'rotate-90 bg-orange-500 text-white' : '' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                        @else
                                            {{-- Пустышка-отступ, если детей нет (чтобы выровнять текст) --}}
                                            <div class="w-6 h-6 shrink-0"></div>
                                        @endif

                                        {{-- Клик по тексту загружает все товары категории --}}
                                        <a href="{{ route('category.products', $rootCat->slug) }}"
                                           class="text-sm font-bold truncate transition-colors {{ $isCurrentRootActive ? 'text-orange-600 font-black' : 'text-gray-800 group-hover/row:text-orange-600' }}">
                                            {{ $rootCat->name }}
                                        </a>
                                    </div>
                                </div>

                                {{-- Контейнер подкатегорий (Скрыт по умолчанию, если не открыт) --}}
                                @if($hasChildren)
                                    <div id="children-container-{{ $rootCat->id }}"
                                         class="children-container pl-7 mt-1 space-y-0.5 transition-all duration-200 {{ $isOpen ? 'block' : 'hidden' }}">
                                        @foreach($children as $subCat)
                                            <a href="{{ route('category.products', $subCat->slug) }}"
                                               class="flex items-center justify-between px-3 py-1.5 text-xs font-medium rounded-lg transition-all duration-150
                                  {{ isset($category) && $category->id === $subCat->id
                                     ? 'bg-orange-500 text-white font-bold shadow-xs'
                                     : 'text-gray-600 hover:bg-gray-50 hover:text-orange-600' }}">
                                                <span class="truncate">{{ $subCat->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @else
                        <p class="text-xs text-gray-400 italic px-2">Категории не найдены</p>
                    @endif
                </nav>
            </aside>

            {{-- ПРАВАЯ КОЛОНКА: Основной контент с товарами --}}
            <section class="flex-1 w-full">

                {{-- Хлебные крошки / Заголовок категории --}}
                {{--<div class="mb-4 px-1">
                    <h1 class="text-xl font-black text-gray-900">
                        {{ $category->name ?? 'Все товары' }}
                    </h1>
                    <p class="text-xs text-gray-400 mt-1">
                        Найдено: <span class="text-gray-700 font-bold">{{ $products->count() ?? 0 }}</span> предложений
                    </p>
                </div>--}}

                {{-- Upper Panel: Сортировка и Поиск --}}
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between mb-6 bg-white p-3 rounded-2xl border border-gray-100 shadow-xs">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider pl-1">Сортировка:</span>
                        <select class="px-3 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 transition cursor-pointer font-bold text-gray-700">
                            <option value="popular">По популярности</option>
                            <option value="price_asc">Сначала дешевые</option>
                            <option value="price_desc">Сначала дорогие</option>
                        </select>
                    </div>

                    <div class="relative w-full sm:w-64">
                        <input type="text" placeholder="Поиск в категории..."
                               class="w-full pl-8 pr-3 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition" />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- СЕТКА ТОВАРОВ: ТРИ КОЛОНКИ НА ДЕСКТОПЕ --}}
                {{-- grid-cols-2 на мобилках, md:grid-cols-2 для планшетов, xl:grid-cols-3 для мониторов --}}
                <div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @include('_partials._products')
                </div>

            </section>

        </div>
    </main>
@stop

<script>
    function toggleCategoryTree(event, categoryId) {
        // Останавливаем всплытие, чтобы не срабатывал переход по ссылке (на всякий случай)
        event.stopPropagation();
        event.preventDefault();

        const container = document.getElementById(`children-container-${categoryId}`);
        const btn = event.currentTarget;

        if (container) {
            if (container.classList.contains('hidden')) {
                // Открываем
                container.classList.remove('hidden');
                btn.classList.add('rotate-90', 'bg-orange-500', 'text-white');
            } else {
                // Закрываем
                container.classList.add('hidden');
                btn.classList.remove('rotate-90', 'bg-orange-500', 'text-white');
            }
        }
    }
</script>
