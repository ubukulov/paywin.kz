@extends('partner.partner')

@push('partner_styles')
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        /* Копируем все стили из create.blade.php */
        :root { --primary: #6366f1; --primary-dark: #4f46e5; }
        body { background-color: #f1f5f9; }
        .form-container { max-width: 850px; margin: 0 auto; }
        input, select, textarea { padding: 14px 20px !important; background: #f1f5f9; border: 2px solid transparent; border-radius: 16px; width: 100%; }
        input:focus { background: #fff; border-color: var(--primary); outline: none; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-radius: 28px; padding: 32px; box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04); margin-bottom: 24px; }
        .section-header { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
        .section-header i { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; border-radius: 12px; }
        .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 16px; }
        .photo-item { aspect-ratio: 1; border-radius: 20px; overflow: hidden; position: relative; background: #f1f5f9; }
        .photo-item img { width: 100%; height: 100%; object-fit: cover; }
        .btn-remove { position: absolute; top: 8px; right: 8px; width: 26px; height: 26px; background: #ef4444; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; font-size: 12px; }
        .upload-btn { border: 2px dashed #cbd5e1; cursor: pointer; display: flex; flex-direction: column; items: center; justify-content: center; }
        .main-submit { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; width: 100%; padding: 20px; border-radius: 20px; font-weight: 800; cursor: pointer; }
        .warehouse-box { background: white; border-radius: 20px; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 16px; }
        label { font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; display: block; }
    </style>
@endpush

@section('content')
    <div id="editGood" class="py-10 px-4">
        <div class="form-container">

            <div class="mb-10 flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-black text-slate-900">Редактирование</h1>
                    <p class="text-slate-500 mt-2">ID товара: #@{{ product_id }}</p>
                </div>
                <a href="{{ route('partner.product.index') }}" class="text-slate-400 hover:text-slate-600 font-bold text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Назад к списку
                </a>
            </div>

            <div class="glass-card">
                <div class="section-header">
                    <i class="fas fa-images"></i>
                    <h3>Фотографии</h3>
                </div>
                <div class="photo-grid">
                    <div v-for="(p, index) in existingPhotos" :key="'old-'+index" class="photo-item shadow-sm border-2 border-indigo-100">
                        <img :src="p.url" />
                        <button type="button" class="btn-remove" @click="removeExistingPhoto(index)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div v-for="(p, index) in photos" :key="'new-'+index" class="photo-item shadow-sm">
                        <img :src="p" />
                        <div class="absolute bottom-2 left-2 bg-green-500 text-white text-[8px] px-1 rounded font-bold">NEW</div>
                        <button type="button" class="btn-remove" @click="removePhoto(index)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div v-if="(photos.length + existingPhotos.length) < 15" class="photo-item upload-btn" @click="triggerUpload">
                        <i class="fas fa-plus-circle text-2xl mb-2"></i>
                        <span class="text-[10px] font-black uppercase">Добавить</span>
                    </div>
                </div>
                <input type="file" id="uploadInput" hidden multiple accept="image/*" @change="handleUpload">
            </div>

            <div class="glass-card">
                <div class="section-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Информация</h3>
                </div>
                <div class="space-y-6">
                    <div>
                        <label>Категория</label>
                        <select v-model="product_category_id">
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">@{{ cat.name }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label>Артикул</label>
                            <input type="text" v-model="article">
                        </div>
                        <div class="md:col-span-2">
                            <label>Название</label>
                            <input type="text" v-model="name">
                        </div>
                    </div>
                    <div>
                        <label>Описание</label>
                        <textarea v-model="description" rows="4"></textarea>
                    </div>
                </div>
            </div>

            <div class="glass-card">
                <div class="section-header">
                    <i class="fas fa-tags"></i>
                    <h3>Цена и наличие</h3>
                </div>
                <div v-for="point in warehouses" :key="point.id" class="warehouse-box">
                    <h4 class="font-bold text-slate-800 text-sm mb-4">@{{ point.city.name }} — @{{ point.name }}</h4>
                    <div class="grid grid-cols-2 gap-6">
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
                <button @click="updateProduct" :disabled="loading" class="main-submit">
                    <span v-if="!loading">СОХРАНИТЬ ИЗМЕНЕНИЯ</span>
                    <span v-else><i class="fas fa-circle-notch fa-spin"></i> СОХРАНЕНИЕ...</span>
                </button>
            </div>
        </div>
    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const { createApp, ref } = Vue
        createApp({
            setup() {
                const loading = ref(false);
                const product_id = {{ $product->id }};

                // Данные для предзаполнения
                const article = ref("{{ $product->sku }}");
                const name = ref("{{ $product->name }}");
                const description = ref(`{{ $product->description }}`);
                const product_category_id = ref({{ $product->product_category_id }});

                // Фотографии
                const existingPhotos = ref({!! json_encode($product->images) !!}); // Старые фото из БД
                const photos = ref([]); // Превью новых фото
                const files = ref([]);  // Файлы новых фото
                const removedExistingIds = ref([]); // ID старых фото, которые нужно удалить

                const warehouses = {!! json_encode($warehouses) !!};
                const categories = {!! json_encode($productCategories) !!};
                const points = ref({});

                // Заполняем цены и остатки по складам из существующих данных
                const stocks = {!! json_encode($product->stocks->keyBy('id')) !!};
                warehouses.forEach(p => {
                    const stock = stocks[p.id] ? stocks[p.id] : null;
                    console.log(stock)
                    points.value[p.id] = {
                        price: stock ? stock.price : null,
                        count: stock ? stock.quantity : null
                    };
                });

                const triggerUpload = () => document.getElementById("uploadInput").click();

                const handleUpload = (event) => {
                    let selectedFiles = event.target.files;
                    [...selectedFiles].forEach(file => {
                        files.value.push(file);
                        let reader = new FileReader();
                        reader.onload = e => photos.value.push(e.target.result);
                        reader.readAsDataURL(file);
                    });
                };

                const removePhoto = (index) => {
                    photos.value.splice(index, 1);
                    files.value.splice(index, 1);
                };

                const removeExistingPhoto = (index) => {
                    removedExistingIds.value.push(existingPhotos.value[index].id);
                    existingPhotos.value.splice(index, 1);
                };

                const updateProduct = () => {
                    loading.value = true;
                    let formData = new FormData();
                    formData.append('article', article.value);
                    formData.append('name', name.value);
                    formData.append('product_category_id', product_category_id.value);
                    formData.append('description', description.value);
                    formData.append('warehouses', JSON.stringify(points.value));

                    // Отправляем ID удаленных старых фотографий
                    formData.append('removed_photos', JSON.stringify(removedExistingIds.value));

                    // Отправляем новые файлы
                    files.value.forEach(file => formData.append('new_photos[]', file));

                    axios.post(`/partner/products/${product_id}/update`, formData, {
                        headers: { "Content-Type": "multipart/form-data" }
                    })
                        .then(() => window.location.href = '/partner/products')
                        .catch(err => {
                            alert("Ошибка при сохранении");
                            loading.value = false;
                        });
                };

                return {
                    loading, product_id, photos, existingPhotos, article, name, description,
                    warehouses, points, categories, product_category_id,
                    triggerUpload, handleUpload, removePhoto, removeExistingPhoto, updateProduct
                };
            }
        }).mount('#editGood')
    </script>
@endpush
