@extends('partner.partner')
@section('content')
    <div class="container mt-4">
        @foreach($images as $image)
            <div style="width: 450px; margin-bottom: 10px;display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;">
                <img style="width: 100%; height: 250px;" src="{{ asset($image->image) }}" alt="image">
                <a href="{{ route('partner.imageDelete', ['id' => $image->id]) }}">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        @endforeach
    </div>
@stop
