@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .kaspi-form {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .kaspi-form h2 {
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #777;
            margin-bottom: 20px;
        }

        .block-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .photo-upload {
            margin-bottom: 25px;
        }

        .upload-box {
            width: 110px;
            height: 110px;
            border: 2px dashed #c7c7c7;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .upload-box .icon {
            font-size: 28px;
        }

        .upload-box .plus {
            font-size: 20px;
            margin-top: 5px;
        }

        .req {
            display: block;
            margin-top: 10px;
            font-size: 13px;
            color: #006be5;
        }

        .kaspi-form label {
            font-weight: 600;
            margin-top: 15px;
            display: block;
        }

        .kaspi-form input,
        .kaspi-form select,
        .kaspi-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #d2d2d2;
            border-radius: 8px;
            font-size: 15px;
            background: #fff;
        }

        textarea {
            resize: vertical;
            height: 90px;
        }

        .radio-row {
            margin-top: 5px;
            display: flex;
            gap: 25px;
            font-size: 15px;
        }

        .submit-btn {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            border: none;
            background: #e83b3b;
            color: #fff;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #c12727;
        }

    </style>
@endpush

@section('content')
    <div class="mypromo">
        <div class="mypromo__header">
            <div class="mypromo__header-title">
                СОЗДАВАЙТЕ <br> КРУТЫЕ ТОВАРЫ
            </div>
            <button class="mypromo__header-btn">+ новый товар</button>
        </div>

        <div id="createGood" class="kaspi-form">

            @if(count($warehouses))
            <h2>Создание товара</h2>
            <p class="subtitle">Подробно заполните указанные ниже поля.</p>

            <!-- SWIPER PREVIEW -->
            <div v-if="photos.length > 0" class="swiper mySwiper" style="width: 200px; height: 200px;" >
                <div class="swiper-wrapper">
                    <div class="swiper-slide" v-for="(p, index) in photos" :key="index">
                        <img :src="p" style="height: 100%;" />
                    </div>
                </div>
            </div>

            <!-- Upload -->
            <label class="block-title">Загрузите от 1 до 15 фото</label>
            <input type="file" id="uploadInput" hidden multiple accept="image/*"
                   @change="handleUpload">

            <div class="upload-box" @click="triggerUpload">
                📷
                <span style="font-size:20px; margin-top:5px;">+</span>
            </div>

            <label>Укажите Категорию</label>
            <select v-model="product_category_id">
                <option v-for="(category, index) in categories" :value="category.id">@{{ category.name }}</option>
            </select>

            <label>Артикул</label>
            <input type="text" v-model="article" placeholder="694173838">

            <label>Название товара</label>
            <input type="text" v-model="name" placeholder="Введите название">

            <label>Описание товара</label>
            <textarea v-model="description" placeholder="Введите описание"></textarea>

            <h3 style="text-align: center;">Цена и остатки</h3>

            <div v-for="point in warehouses" :key="point.id" style="margin-bottom: 10px; margin-top: 10px;">
                <div>
                    <strong>@{{ point.city.name }}</strong>
                    <label>Цена</label>
                    <input type="number" min="100" v-model="points[point.id].price">

                    <label>Кол-во</label>
                    <input type="number" min="1" max="10000" v-model="points[point.id].count">
                    <div style="margin-top: 5px; font-size: 14px;">@{{ point.name }}, @{{ point.address }}</div>
                </div>
            </div>

            <button @click="createProduct" class="submit-btn">Создать товар</button>

            @else
                <h4>У вас нет магазины/склады</h4>
                <p>В первую очеред создайте магазин/склад перед добавлением товара</p>
                <a class="btn btn-success" href="{{ route('partner.store.create') }}">Создать</a>
            @endif
        </div>


    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        const { createApp, ref } = Vue

        createApp({
            setup() {
                const photos = ref([]);
                const article = ref("");
                const product_category_id = ref(0);
                const name = ref("");
                const description = ref("");
                const price = ref("");
                const stocks = ref({});
                const files = ref([]);

                const warehouses = JSON.parse(`{!! json_encode($warehouses) !!}`);
                const categories = JSON.parse(`{!! json_encode($productCategories) !!}`);

                const points = ref({});

                warehouses.forEach(p => {
                    points.value[p.id] = {
                        price: null,
                        count: null
                    };
                });

                const triggerUpload = () => {
                    document.getElementById("uploadInput").click();
                };

                const handleUpload = (event) => {
                    let selectedFiles = event.target.files;

                    if (files.value.length + selectedFiles.length > 15) {
                        alert("Максимум 15 фото!");
                        return;
                    }

                    [...selectedFiles].forEach(file => {
                        files.value.push(file);

                        // Preview
                        let reader = new FileReader();
                        reader.onload = e => {
                            photos.value.push(e.target.result);
                            nextTickSwiperInit();
                        };
                        reader.readAsDataURL(file);
                    });
                };

                const nextTickSwiperInit = () => {
                    setTimeout(() => {
                        new Swiper(".mySwiper", {
                            slidesPerView: "auto",
                            spaceBetween: 10,
                        });
                    }, 50);
                };

                const createProduct = () => {
                    console.log({
                        article: article.value,
                        name: name.value,
                        description: description.value,
                        price: price.value,
                        stocks: stocks.value,
                        photos: photos.value.length
                    });

                    let formData = new FormData();

                    formData.append('article', article.value);
                    formData.append('name', name.value)
                    formData.append('description', description.value)
                    formData.append('warehouses', JSON.stringify(points.value))

                    files.value.forEach((file, index) => {
                        formData.append('photos[]', file);
                    });


                    axios.post("/partner/products/store", formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data"
                            }
                        }
                    )
                        .then(res => {
                            window.location.href = '/partner/products';
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Ошибка при создании товара");
                        });
                };

                return {
                    photos, article, name, description, price, stocks,
                    triggerUpload, handleUpload, createProduct, warehouses, points
                };
            }
        }).mount('#createGood')
    </script>
@endpush
