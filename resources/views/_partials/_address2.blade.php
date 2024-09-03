<div class="partdescr__address">
    <div class="partdescr__address-title">Адреса ({{ $partner->address->count() }})</div>

    @if($partner->address->count() == 0)
        <div class="partner__address-subtitle">Вы еще не указали адрес заведения</div>
    @else
{{--        <div id="map" style="width: 100%; height: 200px;"></div>--}}
        <div class="partdescr__address-slider swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden">
            <div class="swiper-wrapper" id="swiper-wrapper-439710e10f6ea6a243" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
                @foreach($partner->address as $address)
                    <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 3" style="width: 480px;">
                        <div class="partdescr__address-info">
                            <div class="partdescr__address-info-block">
                                <img src="/images/partner-description/location.svg" alt="icon">
                                <div class="partdescr__address-text">
                                    {!! $address->address !!}
                                </div>
                            </div>
                        </div>
                        <div class="partdescr__address-map">
                            <img src="/images/partner-description/partner-map.png" alt="map">
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination swiper-pagination-bullets swiper-pagination-horizontal"><span class="swiper-pagination-bullet swiper-pagination-bullet-active" aria-current="true"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span></div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
        </div>
    @endif

    @if(Auth::check() && Auth::user()->user_type == 'partner' && Route::currentRouteName() == 'partner.cabinet')
        <a href="{{ route('partner.addressCreate') }}" class="partner__address-upload-btn">+ добавить адрес</a>
    @endif

    <script src="https://api-maps.yandex.ru/2.1/?apikey=<?= env('YANDEX_API_KEY') ?>&lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        ymaps.ready(init);
        function init() {
            // Создаем карту с центром в Москве
            var myMap = new ymaps.Map("map", {
                center: [69.50, 42.36], // Центр карты (Москва)
                zoom: 10
            });

            // Указываем адрес для геокодирования
            var address = 'Шымкент, Ташкентская улица, 20';

            // Выполняем запрос к геокодеру
            ymaps.geocode(address).then(function (res) {
                // Получаем первый результат из ответа геокодера
                var firstGeoObject = res.geoObjects.get(0);

                // Получаем координаты объекта
                var coords = firstGeoObject.geometry.getCoordinates();

                // Добавляем метку на карту
                var placemark = new ymaps.Placemark(coords, {
                    balloonContent: 'Адрес: ' + address
                });

                // Центрируем карту на найденные координаты и добавляем метку
                myMap.setCenter(coords, 12);
                myMap.geoObjects.add(placemark);
            });
        }
    </script>
</div>
