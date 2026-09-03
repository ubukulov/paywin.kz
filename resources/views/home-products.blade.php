@extends('layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 pb-12 mt-6">

        {{-- Главный контейнер --}}
        <div class="flex flex-col lg:flex-row gap-6 items-start relative">

            {{-- ПРАВАЯ КОЛОНКА: Основной контент с товарами --}}
            <section class="flex-1 w-full">

                {{-- Верхняя панель: Сортировка, Поиск и Категории --}}
                <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-xs mb-6 space-y-4">

                    {{-- Строка 1: Сортировка и Поиск --}}
                    <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider pl-1">Сортировка:</span>
                            {{-- Селект сортировки с сохранённым значением при первой загрузке --}}
                            <select id="product-sort-select" class="px-3 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 transition cursor-pointer font-bold text-gray-700 w-full sm:w-auto">
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>По популярности</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Сначала дешевые</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Сначала дорогие</option>
                            </select>
                        </div>

                        {{-- Инпут поиска --}}
                        <div class="relative w-full sm:w-64">
                            <input type="text" id="product-search-input" placeholder="Поиск товаров..."
                                   class="w-full pl-8 pr-3 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition" />
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    {{-- Строка 2: Категории (Красивая гибкая сетка в несколько строк) --}}
                    <div id="category-pills" class="flex flex-wrap items-center gap-2 pt-3 border-t border-gray-100">
                        <button type="button"
                                data-category-id=""
                                class="category-btn active inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all duration-200 bg-orange-500 text-white shadow-xs hover:bg-orange-600 active:scale-95">
                            <span>Все товары</span>
                        </button>

                        @foreach($categories as $cat)
                            <button type="button"
                                    data-category-id="{{ $cat->id }}"
                                    class="category-btn inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all duration-200 bg-gray-50 text-gray-600 border border-gray-100 hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 active:scale-95">
                                <span>{{ $cat->name }}</span>
                                @if(isset($cat->products_count))
                                    <span class="text-[10px] opacity-70 bg-gray-200/60 px-1.5 py-0.2 rounded-md font-extrabold group-hover:bg-orange-100">
                                        {{ $cat->products_count }}
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>

                </div>

                {{-- СЕТКА ТОВАРОВ --}}
                <div id="products-container" class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @include('_partials._products')
                </div>

                {{-- Индикатор загрузки при скролле --}}
                <div id="infinite-scroll-sentinel" class="py-8 flex justify-center items-center" data-next-page="{{ $products->nextPageUrl() }}">
                    <div id="loading-spinner" class="hidden flex items-center gap-2 text-orange-500 font-bold text-sm">
                        <svg class="animate-spin h-5 w-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Загрузка товаров...</span>
                    </div>
                </div>

            </section>

        </div>
    </main>
@stop

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sentinel = document.getElementById('infinite-scroll-sentinel');
        const container = document.getElementById('products-container');
        const spinner = document.getElementById('loading-spinner');
        const searchInput = document.getElementById('product-search-input');
        const sortSelect = document.getElementById('product-sort-select');
        const categoryBtns = document.querySelectorAll('.category-btn');

        let isLoading = false;
        let searchTimeout = null;
        let activeCategoryId = '';

        if (!sentinel || !container) return;

        // Observer для скролла
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !isLoading) {
                    const nextPageUrl = sentinel.getAttribute('data-next-page');
                    if (nextPageUrl) {
                        loadMoreProducts(nextPageUrl, false);
                    }
                }
            });
        }, { rootMargin: '200px' });

        observer.observe(sentinel);

        function loadMoreProducts(url, isReset = false) {
            isLoading = true;
            spinner.classList.remove('hidden');

            const fetchUrl = new URL(url, window.location.origin);

            if (searchInput && searchInput.value.trim() !== '') {
                fetchUrl.searchParams.set('search', searchInput.value.trim());
            }
            if (activeCategoryId) {
                fetchUrl.searchParams.set('category_id', activeCategoryId);
            }
            if (sortSelect && sortSelect.value) {
                fetchUrl.searchParams.set('sort', sortSelect.value);
            }

            fetch(fetchUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.json())
                .then(data => {
                    if (isReset) {
                        container.innerHTML = data.html || '<div class="col-span-full text-center py-12 text-xs font-bold text-gray-400">Товары не найдены</div>';
                    } else if (data.html) {
                        container.insertAdjacentHTML('beforeend', data.html);
                    }

                    if (data.next_page) {
                        sentinel.setAttribute('data-next-page', data.next_page);
                        observer.observe(sentinel);
                    } else {
                        sentinel.removeAttribute('data-next-page');
                        observer.unobserve(sentinel);
                    }
                })
                .catch(error => console.error('Ошибка загрузки товаров:', error))
                .finally(() => {
                    isLoading = false;
                    spinner.classList.add('hidden');
                });
        }

        // 1. Поиск по тексту
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    loadMoreProducts(window.location.pathname, true);
                }, 400);
            });
        }

        // 2. Выбор категории
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                categoryBtns.forEach(b => {
                    b.classList.remove('bg-orange-500', 'text-white', 'shadow-xs');
                    b.classList.add('bg-gray-50', 'text-gray-600', 'border', 'border-gray-100');
                });

                this.classList.remove('bg-gray-50', 'text-gray-600', 'border', 'border-gray-100');
                this.classList.add('bg-orange-500', 'text-white', 'shadow-xs');

                activeCategoryId = this.getAttribute('data-category-id');
                loadMoreProducts(window.location.pathname, true);
            });
        });

        // 3. Изменение сортировки
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                loadMoreProducts(window.location.pathname, true);
            });
        }
    });
</script>
