<script src="https://api-maps.yandex.ru/2.1/?apikey=<?= env('YANDEX_API_KEY') ?>&lang=ru_RU" type="text/javascript"></script>

<div class="partdescr__address">
    <div class="partdescr__address-title">Адреса ({{ $partner->address->count() }})</div>

    @if($partner->address->count() == 0)
        <div class="partner__address-subtitle">Вы еще не указали адрес заведения</div>
    @else
        @php
            $arr = [];
            foreach($partner->address as $item){
                $arr[$item->id] = $item->toArray();
                $arr[$item->id]['address'] = str_replace('<br>', '', $item->address);
            }

            $addresses = $arr;
        @endphp
        <div class="yanmaps">
            <div class="slider-container">
            @foreach($addresses as $item)
                <div class="yanmaps-item">
                    <div id="map{{$item['id']}}" style="width: 100%; height: 200px;"></div>
                    <div>{{ $item['address'] }}</div>
                </div>

                <script type="text/javascript">
                    ymaps.ready(init);
                    function init() {
                        // Создаем карту с центром в Москве
                        var myMap = new ymaps.Map("map<?=$item['id']?>", {
                            center: [69.50, 42.36], // Центр карты (Москва)
                            zoom: 2
                        });

                        // Указываем адрес для геокодирования
                        var address = "<?=$item['address']?>";

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
                            myMap.setCenter(coords, 16);
                            myMap.geoObjects.add(placemark);
                        });
                    }
                </script>
            @endforeach
            </div>
            <div class="dots-container">
                @foreach($addresses as $key=>$val)
                <span class="dot" data-slide="{{$key}}"></span>
                @endforeach
            </div>
        </div>

        <style>
            .yanmaps {
                position: relative;
                width: 100%;
                overflow: hidden;
                display: flex;
                align-items: center;
            }
            .slider-container {
                display: flex;
                transition: transform 0.5s ease-in-out;
                width: 100%;
            }
            .yanmaps-item {
                min-width: 100%; /* Каждая карточка будет занимать 100% ширины контейнера */
                box-sizing: border-box;
                padding: 20px;
                text-align: center;
                background-color: #f2f2f2;
                border: 1px solid #ccc;
            }
            .dots-container {
                text-align: center;
                margin-top: 20px;
            }
            .dot {
                height: 15px;
                width: 15px;
                margin: 0 5px;
                background-color: #bbb;
                border-radius: 50%;
                display: inline-block;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .dot.active {
                background-color: #717171;
            }
        </style>
    @endif

    @if(Auth::check() && Auth::user()->user_type == 'partner' && Route::currentRouteName() == 'partner.cabinet')
        <a href="{{ route('partner.addressCreate') }}" class="partner__address-upload-btn">+ добавить адрес</a>
    @endif

</div>
