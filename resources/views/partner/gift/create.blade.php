@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f6f7;
            padding: 20px;
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
                СОЗДАВАЙТЕ <br> КРУТЫЕ ПОДАРКИ
            </div>
            <button class="mypromo__header-btn">+ новый подарок</button>
        </div>

        <div id="createGood" class="kaspi-form">
            <form action="{{ route('partner.gift.store') }}" method="post">
                @csrf
            <h2>Создание подарка</h2>
            <p class="subtitle">Подробно заполните указанные ниже поля.</p>

            <label>Название</label>
            <input type="text" name="title" placeholder="Скидка 10%" required>

            <label>Описание</label>
            <textarea name="description" placeholder="Введите описание"></textarea>

            <label>Тип подарка</label>
            <select name="type">
                <option value="physical">Физический подарок</option>
                <option value="discount">Cкидка</option>
                <option value="cashback">Кешбек</option>
                <option value="promocode">Промокод</option>
            </select>

            <h3 style="text-align: center; margin-top: 20px;">Правила выигрыш</h3>

            <label>Минимальная сумма заказа</label>
            <input type="number" min="1000" value="1000" name="min_order_total" required>

            <label>Максимальная сумма заказа</label>
            <input type="number" min="2000" value="2000" name="max_order_total" required>

            <label>Вероятность выигрыша, %</label>
            <input type="number" min="0" max="100" value="10" name="chance" required>

            <label>Сколько раз один пользователь может выиграть этот подарок?</label>
            <input type="number" min="0" name="max_per_user" value="0" required>

            <button type="submit" class="submit-btn">Создать товар</button>
            </form>
        </div>
    </div>
@stop
