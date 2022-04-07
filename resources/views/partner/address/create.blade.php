@extends('partner.partner')
@section('content')
    <div class="container mt-4">
        <form action="{{ route('partner.addressStore') }}" method="post">
            @csrf
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="address">
                        Адрес
                    </span>
                </div>
                <input type="text" class="form-control" required name="address" aria-describedby="address">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="latitude">
                        Широта
                    </span>
                </div>
                <input type="text" class="form-control" name="latitude" aria-describedby="latitude">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="longitude">
                        Долгота
                    </span>
                </div>
                <input type="text" class="form-control" name="longitude" aria-describedby="longitude">
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </div>
                </div>

                <div class="col-md-6 text-right">
                    <a href="{{ route('partner.cabinet') }}" class="btn btn-warning">Назад</a>
                </div>
            </div>
        </form>
    </div>
@stop
