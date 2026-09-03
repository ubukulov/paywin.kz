@extends('layouts.app')

@section('content')
    <main class="max-w-7xl mx-auto px-4 pb-12 mt-6">

        {{-- Главный контейнер --}}
        <div class="flex flex-col lg:flex-row gap-6 items-start relative">

            {{-- ПРАВАЯ КОЛОНКА: Основной контент с товарами --}}
            <section class="flex-1 w-full">

                {{-- Upper Panel: Сортировка и Поиск --}}
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between mb-6 bg-white p-3 rounded-2xl border border-gray-100 shadow-xs">
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider pl-1">Сортировка:</span>
                        <select class="px-3 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 transition cursor-pointer font-bold text-gray-700 w-full sm:w-auto">
                            <option value="popular">По популярности</option>
                            <option value="price_asc">Сначала дешевые</option>
                            <option value="price_desc">Сначала дорогие</option>
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

        let isLoading = false;
        let searchTimeout = null;

        if (!sentinel) return;

        // Создаем observer для отслеживания прокрутки до низа страницы
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !isLoading) {
                    const nextPageUrl = sentinel.getAttribute('data-next-page');

                    if (nextPageUrl) {
                        loadMoreProducts(nextPageUrl);
                    }
                }
            });
        }, {
            rootMargin: '200px', // Начинает подгрузку за 200px до достижения низа страницы
        });

        observer.observe(sentinel);

        function loadMoreProducts(url, isNewSearch = false) {
            isLoading = true;
            spinner.classList.remove('hidden');

            // Если это новый поиск, прокидываем текущую поисковую строку
            const fetchUrl = new URL(url, window.location.origin);
            if (searchInput && searchInput.value.trim() !== '') {
                fetchUrl.searchParams.set('search', searchInput.value.trim());
            }

            fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (isNewSearch) {
                        // При поиске полностью перезаписываем контейнер
                        container.innerHTML = data.html || '<div class="col-span-full text-center py-8 text-xs text-gray-400">Товары не найдены</div>';
                    } else if (data.html) {
                        // При скролле дописываем снизу
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

        // Слушатель ввода поиска с задержкой 400мс (debounce)
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const baseUrl = window.location.pathname;
                    loadMoreProducts(baseUrl, true);
                }, 400);
            });
        }
    });

    // Раскрытие дерева категорий
    function toggleCategoryTree(event, categoryId) {
        event.stopPropagation();
        event.preventDefault();

        const container = document.getElementById(`children-container-${categoryId}`);
        const btn = event.currentTarget;

        if (container) {
            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                btn.classList.add('rotate-90', 'bg-orange-500', 'text-white');
            } else {
                container.classList.add('hidden');
                btn.classList.remove('rotate-90', 'bg-orange-500', 'text-white');
            }
        }
    }

    // Управление мобильным меню (Drawer)
    function toggleMobileCategories() {
        const sidebar = document.getElementById('categories-sidebar');
        const overlay = document.getElementById('mobile-categories-overlay');
        const body = document.body;

        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
            }, 10);
            body.style.overflow = 'hidden';
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
            body.style.overflow = '';
        }
    }
</script>
