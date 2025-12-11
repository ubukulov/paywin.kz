@extends('partner.partner')

@push('partner_styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/e294a4845f.js" crossorigin="anonymous"></script>
@endpush

@section('content')
    <div class="mypromo">
        <div class="mypromo__header">
            <div class="mypromo__header-title">
                СОЗДАВАЙТЕ <br> МАГАЗИН
            </div>
            <a href="{{ route('partner.store.create') }}" class="mypromo__header-btn">+ новый магазин</a>
        </div>

        <div class="mypromo__tabs">
            <div class="tabcontent">
                <div class="tabcontent__wrapper">
                    <table class="table table-bordered">
                        <thead>
                        <th>Город</th>
                        <th>Наименование</th>
                        <th>Адрес</th>
                        </thead>
                        <tbody>
                        @foreach($storePoints as $storePoint)
                            <tr>
                                <td>{{ $storePoint->city->name }}</td>
                                <td>{{ $storePoint->name }}</td>
                                <td>{{ $storePoint->address }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@stop

@push('partner_scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="/js/my-promo-promo.js"></script>
    <script src="/js/about-partner.js"></script>
@endpush
