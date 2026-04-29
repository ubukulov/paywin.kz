@extends('partner.partner')

@push('partner_styles')
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- Стили Quill через Unpkg --}}
    <link href="https://unpkg.com/quill@1.3.6/dist/quill.snow.css" rel="stylesheet">
    <style>
        :root { --primary: #6366f1; --primary-dark: #4f46e5; }
        body { background-color: #f1f5f9; }
        .form-container { max-width: 850px; margin: 0 auto; }

        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
        }

        .section-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
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
            width: 100%;
        }

        .photo-item {
            aspect-ratio: 1 / 1; border-radius: 20px; overflow: hidden; position: relative;
            background: #e2e8f0; border: 2px solid transparent; cursor: grab;
            transition: all 0.3s ease;
        }
        .photo-item img { width: 100%; height: 100%; object-fit: cover; pointer-events: none; }

        .sortable-ghost { opacity: 0.2; background: var(--primary); }
        .sortable-drag { transform: scale(1.05); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

        .btn-remove {
            position: absolute; top: 8px; right: 8px; width: 28px; height: 28px;
            background: #ef4444; border-radius: 50%; color: white;
            display: flex; align-items: center; justify-content: center;
            border: none; cursor: pointer; z-index: 50; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .upload-btn {
            border: 2px dashed #cbd5e1; background: #f8fafc;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            cursor: pointer; color: #64748b;
        }

        input, select {
            width: 100%; background: #f1f5f9; border: 2px solid transparent;
            border-radius: 16px; padding: 14px 20px; font-size: 15px; transition: 0.2s;
        }
        input:focus, select:focus { background: white; border-color: var(--primary); outline: none; }

        /* WYSIWYG Editor */
        .editor-wrapper { border-radius: 16px; overflow: hidden; background: #f1f5f9; border: 2px solid transparent; }
        .editor-wrapper:focus-within { background: white; border-color: var(--primary); }
        .ql-toolbar.ql-snow { border: none !important; background: white; border-bottom: 1px solid #e2e8f0 !important; }
        .ql-container.ql-snow { border: none !important; min-height: 200px; }

        .warehouse-box { background: white; border-radius: 20px; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 16px; }

        .main-submit {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; width: 100%; padding: 20px; border-radius: 20px;
            font-weight: 800; cursor: pointer; border: none; transition: 0.3s;
        }
        .main-submit:disabled { opacity: 0.6; }
    </style>
@endpush

@section('content')
    <div id="editGood" class="py-10 px-4">
        <div class="form-container">

            <div class="mb-8 flex justify-between items-center animate__animated animate__fadeIn">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight">Редактирование</h1>
                    <p class="text-slate-500 mt-2">Товар: <span class="text-indigo-600 font-bold">#@{{ product_id }}</span></p>
                </div>
                <a href="{{ route('partner.product.index') }}" class="bg-white px-5 py-2.5 rounded-xl text-slate-400 hover:text-slate-600 font-bold text-sm shadow-sm transition">
                    <i class="fas fa-arrow-left mr-1"></i> К списку
                </a>
            </div>

            {{-- ФОТО (ЕДИНАЯ СОРТИРОВКА) --}}
            <div class="glass-card animate__animated animate__fadeInUp">
                <div class="section-header">
                    <i class="fas fa-camera"></i>
                    <h3>Фотографии (@{{ images.length }}/15)</h3>
                </div>

                <draggable
                    v-model="images"
                    item-key="id"
                    tag="div"
                    class="photo-grid"
                    ghost-class="sortable-ghost"
                    drag-class="sortable-drag"
                    animation="250"
                >
                    <template #item="{element, index}">
                        <div class="photo-item shadow-sm" :class="{'border-2 border-green-400': element.isNew}">
                            <img :src="element.preview" />

                            <div v-if="index === 0" class="absolute top-2 left-2 bg-indigo-600 text-white text-[8px] px-2 py-1 rounded-lg font-bold uppercase z-10 shadow-md">
                                Обложка
                            </div>

                            <div v-if="element.isNew" class="absolute bottom-2 right-2 bg-green-500 text-white text-[7px] px-1.5 py-0.5 rounded font-black uppercase shadow-sm">
                                NEW
                            </div>

                            <button type="button" class="btn-remove" @click.stop="removePhoto(index)">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </template>

                    <template #footer>
                        <div v-if="images.length < 15" class="photo-item upload-btn shadow-sm" @click="triggerUpload">
                            <i class="fas fa-plus text-xl mb-1"></i>
                            <span class="text-[9px] font-black uppercase">Добавить</span>
                        </div>
                    </template>
                </draggable>

                <input type="file" id="uploadInput" hidden multiple accept="image/*" @change="handleUpload">
                <p class="text-[11px] text-slate-400 mt-4 italic text-center">Вы можете менять порядок старых и новых фото вместе.</p>
            </div>

            {{-- ИНФОРМАЦИЯ --}}
            <div class="glass-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <div class="section-header">
                    <i class="fas fa-pen"></i>
                    <h3>Описание</h3>
                </div>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label>Категория</label>
                            <select v-model="product_category_id">
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">@{{ cat.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label>Артикул (SKU)</label>
                            <input type="text" v-model="article">
                        </div>
                    </div>
                    <div>
                        <label>Название</label>
                        <input type="text" v-model="name">
                    </div>
                    <div>
                        <label>Описание</label>
                        <div class="editor-wrapper">
                            <div id="editor-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- СКЛАДЫ --}}
            <div class="glass-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="section-header">
                    <i class="fas fa-store"></i>
                    <h3>Цена и наличие</h3>
                </div>
                <div v-for="point in warehouses" :key="point.id" class="warehouse-box shadow-sm">
                    <p class="font-bold text-slate-700 mb-4 text-sm italic">@{{ point.city.name }} — @{{ point.name }}</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label>Цена (₸)</label>
                            <input type="number" v-model="points[point.id].price">
                        </div>
                        <div>
                            <label>Остаток (шт)</label>
                            <input type="number" v-model="points[point.id].count">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-20">
                <button @click="updateProduct" :disabled="loading" class="main-submit shadow-xl shadow-indigo-200">
                    <span v-if="!loading">СОХРАНИТЬ ИЗМЕНЕНИЯ</span>
                    <span v-else><i class="fas fa-circle-notch fa-spin"></i> СОХРАНЕНИЕ...</span>
                </button>
            </div>
        </div>
    </div>
@stop

@push('partner_scripts')
    {{-- UNPKG СКРИПТЫ --}}
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="https://unpkg.com/vuedraggable@4.1.0/dist/vuedraggable.umd.js"></script>
    <script src="https://unpkg.com/quill@1.3.6/dist/quill.js"></script>

    <script>
        const { createApp, ref, onMounted } = Vue;

        const app = createApp({
            setup() {
                const loading = ref(false);
                const product_id = {{ $product->id }};

                const article = ref("{{ $product->sku }}");
                const name = ref("{{ $product->name }}");
                const description = ref("");
                const product_category_id = ref({{ $product->product_category_id }});

                // Единый массив для всех фото
                const images = ref([
                        @foreach($product->images as $img)
                    { id: {{ $img->id }}, preview: '{{ $img->url }}', isNew: false, file: null },
                    @endforeach
                ]);

                const removedExistingIds = ref([]); // ID старых фото, которые были удалены пользователем

                const warehouses = {!! json_encode($warehouses) !!};
                const categories = {!! json_encode($productCategories) !!};
                const points = ref({});

                const stocks = {!! json_encode($product->stocks->keyBy('warehouse_id')) !!};
                warehouses.forEach(p => {
                    const stock = stocks[p.id] || null;
                    points.value[p.id] = {
                        price: stock ? stock.price : null,
                        count: stock ? stock.quantity : null
                    };
                });

                onMounted(() => {
                    // Quill инициализация
                    const quill = new Quill('#editor-container', {
                        theme: 'snow',
                        placeholder: 'Описание товара...',
                        modules: { toolbar: [['bold', 'italic'], [{ 'list': 'ordered'}, { 'list': 'bullet' }]] }
                    });

                    // Загружаем старое описание
                    quill.root.innerHTML = `{!! $product->description !!}`;
                    description.value = quill.root.innerHTML;

                    quill.on('text-change', () => {
                        description.value = quill.root.innerHTML;
                    });
                });

                const triggerUpload = () => document.getElementById("uploadInput").click();

                const handleUpload = (event) => {
                    const selectedFiles = Array.from(event.target.files);
                    if (images.value.length + selectedFiles.length > 15) {
                        alert("Максимум 15 фото"); return;
                    }

                    selectedFiles.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = e => {
                            images.value.push({
                                id: 'new-' + Date.now() + Math.random(),
                                preview: e.target.result,
                                isNew: true,
                                file: file
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                    event.target.value = '';
                };

                const removePhoto = (index) => {
                    const item = images.value[index];
                    if (!item.isNew) {
                        removedExistingIds.value.push(item.id);
                    }
                    images.value.splice(index, 1);
                };

                const updateProduct = () => {
                    if (!name.value || images.value.length === 0) {
                        alert("Название и фото обязательны"); return;
                    }

                    loading.value = true;
                    const formData = new FormData();
                    formData.append('article', article.value);
                    formData.append('name', name.value);
                    formData.append('product_category_id', product_category_id.value);
                    formData.append('description', description.value);
                    formData.append('warehouses', JSON.stringify(points.value));

                    // Список ID для удаления на бэкенде
                    formData.append('removed_photos', JSON.stringify(removedExistingIds.value));

                    // Собираем финальный порядок (ID старых и метку для новых)
                    const finalOrder = images.value.map(img => ({
                        id: img.isNew ? null : img.id,
                        isNew: img.isNew
                    }));
                    formData.append('images_order', JSON.stringify(finalOrder));

                    // Отправляем ТОЛЬКО новые файлы
                    images.value.filter(img => img.isNew).forEach(img => {
                        formData.append('new_photos[]', img.file);
                    });

                    axios.post(`/partner/products/${product_id}/update`, formData, {
                        headers: { "Content-Type": "multipart/form-data" }
                    })
                        .then(() => window.location.href = '/partner/products')
                        .catch(err => {
                            console.error(err);
                            alert("Ошибка сохранения");
                            loading.value = false;
                        });
                };

                return {
                    loading, product_id, images, article, name, description,
                    warehouses, points, categories, product_category_id,
                    triggerUpload, handleUpload, removePhoto, updateProduct
                };
            }
        })
            .component('draggable', window.vuedraggable) // Регистрация Draggable
            .mount('#editGood');
    </script>
@endpush
