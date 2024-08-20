@extends('partner.partner')
@section('content')
    <div class="container mt-4">
        <form action="{{ route('partner.profileUpdate') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user_profile->user_id }}">
            <div class="form-group">
                <label>Компания</label>
                <input type="text" name="company" value="{{ $user_profile->company }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Категория</label>
                <select name="category_id" class="form-control">
                    @foreach($categories as $cat)
                    <option @if($cat->id == $user_profile->category_id) selected @endif value="{{ $cat->id }}">{{ $cat->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Телефон</label>
                <input type="text" name="phone" value="{{ $user_profile->phone }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Адрес</label>
                <input type="text" name="address" value="{{ $user_profile->address }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user_profile->email }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Сайт</label>
                <input type="text" name="site" value="{{ $user_profile->site }}" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Время работы</label>
                <textarea rows="3" cols="30" name="work_time" class="form-control">{{ $user_profile->work_time }}</textarea>
            </div>

            <div class="form-group">
                <label>Описание</label>
                <textarea rows="3" cols="30" name="description" class="form-control">{{ $user_profile->description }}</textarea>
            </div>

            <div class="form-group">
                <label>Логотип</label>
                <div class="custom-file mb-3">
                    <input type="file" accept="image/*" name="logo" class="custom-file-input" id="validatedCustomFile" @if(empty($user_profile->logo)) required @endif>
                    <label class="custom-file-label" for="validatedCustomFile">Выберите логотип...</label>
                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                </div>
            </div>

            <div class="form-group">
                <label>Договор</label>
                <div class="custom-file mb-3">
                    <input type="file" accept="application/pdf" name="agreement" class="custom-file-input" id="validatedCustomFile" @if(empty($user_profile->agreement)) required @endif>
                    <label class="custom-file-label" for="validatedCustomFile">Выберите договор...</label>
                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                </div>
            </div>

            <h4 style="color: #ccc;" class="mb-2">Реквизиты</h4>
            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fa fa-bank" aria-hidden="true"></i> Названия банка
                        </span>
                    </div>
                    <input type="text" class="form-control" name="bank_name" value="{{ $user_profile->bank_name }}" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fa fa-usd" aria-hidden="true"></i> Банковский счет
                        </span>
                    </div>
                    <input type="text" class="form-control" name="bank_account" value="{{ $user_profile->bank_account }}" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>

            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">
                            <i class="fa fa-credit-card" aria-hidden="true"></i> Номер карты
                        </span>
                    </div>
                    <input type="text" class="form-control" name="card_number" value="{{ $user_profile->card_number }}" aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </div>

            <h4 style="color: #ccc;" class="mb-2">Социальные сети</h4>

            @include('partner.partials._networks')

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </div>
                </div>

                <div class="col-md-6">
                    <a href="{{ route('partner.cabinet') }}" class="btn btn-warning">Назад</a>
                </div>
            </div>
        </form>
    </div>
@stop
