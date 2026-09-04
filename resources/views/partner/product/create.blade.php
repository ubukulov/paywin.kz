@extends('partner.partner')

@push('partner_styles')
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Стили Quill --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --bg-soft: #f8fafc;
        }

        body { background-color: #f1f5f9; }

        .form-container {
            max-width: 850px;
            margin: 0 auto;
        }

        /* КАРТОЧКИ СЕКЦИЙ */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
        }

        .section-header {
            display: flex;
            align-items: center; gap: 12px; margin-bottom: 24px;
        }
        .section-header i {
            width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; border-radius: 12px; font-size: 18px;
        }
        .section-header h3 { font-size: 18px; font-weight: 800; color: #1e293b; margin: 0; }

        /* ФОТО ГАЛЕРЕЯ */
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 16px;
        }
        .photo-item {
            aspect-ratio: 1; border-radius: 20px; overflow: hidden; position: relative;
            background: #f1f5f9; border: 2px solid transparent; cursor: grab;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .photo-item:active { cursor: grabbing; }
        .photo-item img { width: 100%; height: 100%; object-fit: cover; }

        .sortable-ghost { opacity: 0.3; background: var(--primary); border: 2px dashed var(--primary-dark); }

        .btn-remove {
            position: absolute; top: 8px; right: 8px; width: 30px; height: 30px;
            background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(4px);
            border-radius: 50%; color: #ef4444; display: flex; align-items: center;
            justify-content: center; border: none; cursor: pointer; z-index: 50;
        }

        .upload-btn {
            border: 2px dashed #cbd5e1; display: flex; flex-direction: column;
            align-items: center; justify-content: center; cursor: pointer;
            color: #64748b; background: #f8fafc;
        }

        label { font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block; }

        input, select {
            width: 100%; background: #f1f5f9; border: 2px solid transparent;
            border-radius: 16px; padding: 14px 20px; font-size: 15px; color: #334155; transition: all 0.2s;
        }
        input:focus, select:focus {
            background: #fff; border-color: var(--primary); outline: none;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* Стилизация Quill под общий дизайн */
        .editor-wrapper {
            background: #f1f5f9;
            border-radius: 16px;
            border: 2px solid transparent;
            overflow: hidden;
            transition: all 0.2s;
        }
        .editor-wrapper:focus-within {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        .ql-toolbar.ql-snow {
            border: none !important;
            border-bottom: 1px solid #e2e8f0 !important;
            background: #fff;
        }
        .ql-container.ql-snow {
            border: none !important;
            min-height: 200px;
            font-family: inherit;
            font-size: 15px;
        }

        .warehouse-box { background: white; border-radius: 20px; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 16px; }

        .main-submit {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; width: 100%; padding: 20px; border-radius: 20px;
            font-size: 18px; font-weight: 800; border: none; cursor: pointer;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4); transition: all 0.3s;
        }
        .main-submit:disabled { opacity: 0.7; cursor: not-allowed; }

        /* СТИЛИ ДЛЯ ДИНАМИЧЕСКИХ ХАРАКТЕРИСТИК */
        .feature-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 16px;
            align-items: center;
        }
        .btn-action-sec {
            background: #f1f5f9; color: #64748b; border: none; padding: 14px;
            border-radius: 16px; font-weight: 800; cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-action-sec:hover { background: #e2e8f0; color: #334155; }
        .btn-delete-sec { background: #fef2f2; color: #ef4444; }
        .btn-delete-sec:hover { background: #fee2e2; color: #dc2626; }
    </style>
@endpush

@section('content')
    <div id="createGood" class="py-10 px-4">
        <div class="form-container">

            <div class="mb-10 animate__animated animate__fadeIn">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">Новый <span class="text-indigo-600">товар</span></h1>
                <p class="text-slate-500 mt-2">Первое фото станет обложкой. Порядок можно менять перетаскиванием.</p>
            </div>

            @if(count($warehouses))
                {{-- ФОТОГРАФИИ --}}
                <div class="glass-card animate__animated animate__fadeInUp">
                    <div class="section-header">
                        <i class="fas fa-images"></i>
                        <h3>Фотографии товара</h3>
                    </div>
                    <div class="photo-grid">
                        <draggable
                            v-model="images"
                            item-key="id"
                            tag="div"
                            class="photo-grid col-span-full"
                            ghost-class="sortable-ghost"
                            animation="300"
                        >
                            <template #item="{element, index}">
                                <div class="photo-item shadow-sm">
                                    <img :src="element.preview" />
                                    <div v-if="index === 0" class="absolute top-2 left-2 bg-indigo-600 text-white text-[9px] px-2 py-1 rounded-lg font-bold uppercase z-10 shadow-sm">
                                        Обложка
                                    </div>
                                    <button type="button" class="btn-remove shadow-sm" @click.stop="removePhoto(index)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </template>
                        </draggable>

                        <div v-if="images.length < 15" class="photo-item upload-btn" @click="triggerUpload">
                            <i class="fas fa-plus-circle text-2xl mb-2"></i>
                            <span class="text-[10px] font-black uppercase">Добавить</span>
                        </div>
                    </div>
                    <input type="file" id="uploadInput" hidden multiple accept="image/*" @change="handleUpload">
                </div>

                {{-- ИНФОРМАЦИЯ --}}
                <div class="glass-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                    <div class="section-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Основная информация</h3>
                    </div>

                    <div class="space-y-6">
                        <div>
                            {{--<label>Категория товара</label>
                            <select v-model="product_category_id">
                                <option value="0" disabled>Выберите из списка...</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">@{{ cat.name }}</option>
                            </select>--}}

                            {{-- УДОБНЫЙ ВЫБОР КАТЕГОРИИ С АВТОКОМПЛИТОМ --}}
                            <div class="relative" ref="categoryDropdownRef">
                                <label>Категория товара <span class="text-rose-500">*</span></label>

                                {{-- Поле ввода для поиска и отображения выбранной категории --}}
                                <div class="relative">
                                    <input type="text"
                                           v-model="categorySearch"
                                           @focus="isCategoryDropdownOpen = true"
                                           @input="isCategoryDropdownOpen = true"
                                           placeholder="Начните вводить название категории..."
                                           class="!py-3.5 !px-11 !text-sm cursor-pointer transition rounded-2xl border-slate-200"
                                           :class="{'!border-indigo-500 !bg-white shadow-sm': isCategoryDropdownOpen}">

                                    <i class="fas fa-folder text-indigo-500 absolute left-4 top-1/2 -translate-y-1/2 text-sm pointer-events-none"></i>

                                    {{-- Очистить / Сбросить выбор --}}
                                    <button type="button"
                                            v-if="selectedCategoryName || categorySearch"
                                            @click="clearCategorySelection"
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 text-xs transition">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                {{-- Выпадающий список категорий --}}
                                <div v-if="isCategoryDropdownOpen"
                                     class="absolute z-50 left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 max-h-64 overflow-y-auto p-2 animate__animated animate__fadeIn">

                                    <div v-if="filteredCategories.length === 0" class="p-4 text-center text-xs text-slate-400 font-medium">
                                        Категория «@{{ categorySearch }}» не найдена
                                    </div>

                                    <div v-else class="space-y-1">
                                        <div v-for="cat in filteredCategories"
                                             :key="cat.id"
                                             @click="selectCategory(cat)"
                                             class="flex items-center justify-between p-3 rounded-xl cursor-pointer transition text-xs font-bold"
                                             :class="product_category_id === cat.id ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-slate-50 text-slate-700'">

                                            <div class="flex items-center gap-2">
                                                <i class="fas" :class="product_category_id === cat.id ? 'fa-check-circle text-indigo-600' : 'fa-folder-open text-slate-300'"></i>
                                                <span>@{{ cat.name }}</span>
                                            </div>

                                            {{-- Если есть родительская категория, показываем подсказку --}}
                                            <span v-if="cat.parent" class="text-[10px] text-slate-400 font-normal italic">
                    @{{ cat.parent.name }}
                </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label>Артикул (SKU)</label>
                                <input type="text" v-model="article" placeholder="Напр: 6941738">
                            </div>
                            <div class="md:col-span-2">
                                <label>Название товара</label>
                                <input type="text" v-model="name" placeholder="Напр: Apple iPhone 15 Pro Max">
                            </div>
                        </div>

                        <div>
                            <label>Описание</label>
                            <div class="editor-wrapper">
                                <div id="editor-container"></div>
                            </div>
                        </div>

                        {{-- ДОБАВЛЕНО: Ссылка на видеообзор товара --}}
                        <div class="pt-4 border-t border-slate-100">
                            <label><i class="fab fa-youtube text-red-500 mr-1 text-sm"></i> Видеообзор товара (Ссылка)</label>
                            <input type="url" v-model="video_url" placeholder="Напр: https://www.youtube.com/watch?v=XXXXXX">
                            <p class="text-[10px] text-slate-400 mt-1">Вставьте прямую ссылку на YouTube-видео или обзор товара.</p>
                        </div>

                        {{-- Характеристики товара --}}
                        <div class="pt-4 border-t border-slate-100">
                            <label class="mb-4">Характеристики товара (Опционально)</label>

                            <div class="space-y-3 mb-4">
                                <div v-for="(feature, index) in features" :key="feature.id" class="feature-row animate__animated animate__fadeIn">
                                    <div>
                                        <input type="text" v-model="feature.key" placeholder="Название (напр: Цвет)">
                                    </div>
                                    <div>
                                        <input type="text" v-model="feature.value" placeholder="Значение (напр: Черный)">
                                    </div>
                                    <div>
                                        <button type="button" class="btn-action-sec btn-delete-sec" @click="removeFeature(index)" title="Удалить">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn-action-sec text-xs uppercase" @click="addFeature">
                                <i class="fas fa-plus"></i> Добавить характеристику
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ЦЕНЫ И СКЛАДЫ --}}
                <div class="glass-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                    <div class="section-header">
                        <i class="fas fa-tags"></i>
                        <h3>Цена и наличие</h3>
                    </div>

                    <div v-for="point in warehouses" :key="point.id" class="warehouse-box">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 text-xs">
                                <i class="fas fa-warehouse"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">@{{ point.city.name }}</h4>
                                <p class="text-[11px] text-slate-400">@{{ point.name }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label>Цена (₸)</label>
                                <input type="number" v-model="points[point.id].price" placeholder="0">
                            </div>
                            <div>
                                <label>Остаток (шт)</label>
                                <input type="number" v-model="points[point.id].count" placeholder="0">
                            </div>
                        </div>

                        {{-- ЛОГИКА ПРЕДЗАКАЗА --}}
                        <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center gap-3">
                                <input type="checkbox"
                                       v-model="points[point.id].is_preorder"
                                       :id="'preorder-' + point.id"
                                       class="w-5 h-5 accent-indigo-600 rounded-lg cursor-pointer bg-slate-100 border-none transition-all">
                                <label :for="'preorder-' + point.id" class="cursor-pointer m-0 text-slate-700 font-bold normal-case text-sm select-none">
                                    Это товар под предзаказ
                                </label>
                            </div>
                            <div v-if="points[point.id].is_preorder" class="animate__animated animate__fadeIn">
                                <label>Срок поставки (в днях)</label>
                                <input type="number" v-model="points[point.id].delivery_days" min="1" placeholder="Напр: 5">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-20">
                    <button @click="createProduct" :disabled="loading" class="main-submit">
                        <span v-if="!loading">ОПУБЛИКОВАТЬ ТОВАР</span>
                        <span v-else><i class="fas fa-circle-notch fa-spin"></i> СОХРАНЕНИЕ...</span>
                    </button>
                </div>

            @endif
        </div>
    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="https://unpkg.com/vuedraggable@4.1.0/dist/vuedraggable.umd.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        const { createApp, ref, computed, onMounted, onUnmounted, toRaw } = Vue;
        const draggableComp = window.vuedraggable;

        const app = createApp({
            setup() {
                const loading = ref(false);
                const images = ref([]);
                const article = ref("");
                //const product_category_id = ref(0);
                const name = ref("");
                const description = ref("");
                const video_url = ref("");
                const features = ref([]);

                // Категории
                const product_category_id = ref(0);
                const categorySearch = ref("");
                const isCategoryDropdownOpen = ref(false);
                const categoryDropdownRef = ref(null);

                const warehouses = {!! json_encode($warehouses ?? []) !!};
                const categories = {!! json_encode($productCategories ?? []) !!};
                const points = ref({});

                // Вычисляемое название выбранной категории
                const selectedCategoryName = computed(() => {
                    const found = categories.find(c => c.id === product_category_id.value);
                    return found ? found.name : '';
                });

                // Фильтрация категорий по поисковой строке
                const filteredCategories = computed(() => {
                    if (!categorySearch.value.trim() || categorySearch.value === selectedCategoryName.value) {
                        return categories;
                    }
                    const query = categorySearch.value.toLowerCase().trim();
                    return categories.filter(c => c.name.toLowerCase().includes(query));
                });

                // Выбор категории
                const selectCategory = (cat) => {
                    product_category_id.value = cat.id;
                    categorySearch.value = cat.name;
                    isCategoryDropdownOpen.value = false;
                };

                // Очистка выбора
                const clearCategorySelection = () => {
                    product_category_id.value = 0;
                    categorySearch.value = "";
                    isCategoryDropdownOpen.value = true;
                };

                // Закрытие выпадающего списка при клике вне компонента
                const handleClickOutside = (event) => {
                    if (categoryDropdownRef.value && !categoryDropdownRef.value.contains(event.target)) {
                        isCategoryDropdownOpen.value = false;
                        if (product_category_id.value > 0) {
                            categorySearch.value = selectedCategoryName.value;
                        }
                    }
                };

                warehouses.forEach(p => {
                    points.value[p.id] = {
                        price: null,
                        count: null,
                        is_preorder: false,
                        delivery_days: null
                    };
                });

                onMounted(() => {
                    document.addEventListener('click', handleClickOutside);
                    const editor = document.getElementById('editor-container');
                    if (!editor) {
                        return;
                    }

                    const quill = new Quill(editor, {
                        theme: 'snow',
                        placeholder: 'Опишите преимущества, характеристики и комплектацию товара...',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                ['clean']
                            ]
                        }
                    });

                    quill.on('text-change', () => {
                        description.value = quill.root.innerHTML;
                    });
                });

                onUnmounted(() => {
                    document.removeEventListener('click', handleClickOutside);
                });

                const addFeature = () => {
                    features.value.push({
                        id: Date.now() + Math.random(),
                        key: "",
                        value: ""
                    });
                };

                const removeFeature = (index) => {
                    features.value.splice(index, 1);
                };

                const triggerUpload = () => document.getElementById("uploadInput").click();

                const handleUpload = (event) => {
                    let selectedFiles = event.target.files;
                    if (!selectedFiles || selectedFiles.length === 0) return;

                    if (images.value.length + selectedFiles.length > 15) {
                        alert("Максимально 15 фото");
                        return;
                    }

                    [...selectedFiles].forEach(file => {
                        let reader = new FileReader();
                        reader.onload = e => {
                            images.value.push({
                                id: Date.now() + Math.random(),
                                preview: e.target.result,
                                rawFile: file // Сохраняем оригинальный файл
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                    event.target.value = '';
                };

                const removePhoto = (index) => images.value.splice(index, 1);

                const createProduct = () => {
                    if (!name.value || !product_category_id.value || images.value.length === 0) {
                        alert("Заполните название, категорию и добавьте хотя бы одно фото");
                        return;
                    }

                    loading.value = true;
                    let formData = new FormData();
                    formData.append('article', article.value || '');
                    formData.append('name', name.value);
                    formData.append('product_category_id', product_category_id.value);
                    formData.append('description', description.value || '');
                    formData.append('warehouses', JSON.stringify(points.value));

                    const metaObject = {};
                    features.value.forEach(item => {
                        if (item.key.trim() !== "" && item.value.trim() !== "") {
                            metaObject[item.key.trim()] = item.value.trim();
                        }
                    });

                    if (video_url.value && video_url.value.trim() !== "") {
                        metaObject['system_video_url'] = video_url.value.trim();
                    }

                    formData.append('meta', JSON.stringify(metaObject));

                    // КРИТИЧЕСКОЕ ИСПРАВЛЕНИЕ: Используем toRaw(), чтобы браузеры не портили бинарные данные картинки
                    images.value.forEach(img => {
                        const fileObj = toRaw(img.rawFile || img.file);
                        if (fileObj) {
                            formData.append('photos[]', fileObj);
                        }
                    });

                    // КРИТИЧЕСКОЕ ИСПРАВЛЕНИЕ: Явно передаем CSRF-токен для поддержки всех браузеров
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        || '{{ csrf_token() }}';

                    axios.post("/partner/products/store", formData, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                            "X-CSRF-TOKEN": csrfToken,
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                        .then(res => {
                            window.location.href = '/partner/products';
                        })
                        .catch(err => {
                            console.error('Save Error:', err);

                            // Детальный отклик ошибки для диагностики
                            let errorMsg = "Ошибка при сохранении";
                            if (err.response) {
                                if (err.response.status === 419) {
                                    errorMsg = "Сессия истекла (Ошибка 419). Перезагрузите страницу и попробуйте снова.";
                                } else if (err.response.status === 422) {
                                    const errors = err.response.data.errors;
                                    errorMsg = "Ошибка валидации:\n" + Object.values(errors).flat().join("\n");
                                } else if (err.response.data && err.response.data.message) {
                                    errorMsg = "Ошибка сервера: " + err.response.data.message;
                                }
                            }

                            alert(errorMsg);
                            loading.value = false;
                        });
                };

                return {
                    loading, images, article, name, description, warehouses, points, categories,
                    product_category_id, categorySearch, isCategoryDropdownOpen, categoryDropdownRef,
                    filteredCategories, selectedCategoryName, selectCategory, clearCategorySelection,
                    features, addFeature, removeFeature, video_url,
                    triggerUpload, handleUpload, removePhoto, createProduct
                };
            }
        });

        if (window.vuedraggable) {
            app.component('draggable', draggableComp);
        }
        app.mount('#createGood');
    </script>
@endpush
