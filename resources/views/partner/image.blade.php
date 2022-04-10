@extends('partner.partner')
@section('content')
    <div class="container mt-4">
        <form action="{{ route('partner.imageStore') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                </div>
                <div class="custom-file">
                    <input type="file" name="images[]" required multiple accept="image/*" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                </div>
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
