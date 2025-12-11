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
        <div id="createGood" class="kaspi-form">
            <h2>Создание магазина</h2>
            <p class="subtitle">Подробно заполните указанные ниже поля.</p>
            <form action="{{ route('partner.store.store') }}" method="post">
                @csrf

                <label>Укажите город</label>
                <select name="city_id">
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>

                <label>Названия</label>
                <input type="text" name="name" placeholder="PP1" required>

                <label>Адрес</label>
                <input type="text" name="address" placeholder="улица Каирбекова 16" required>

                <button type="submit" class="submit-btn">Создать магазин</button>
            </form>
        </div>
    </div>
@stop
