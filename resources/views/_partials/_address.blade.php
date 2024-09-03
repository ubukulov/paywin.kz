@php
    $item = $address;
@endphp

<div id="map{{$item->id}}" style="width: 100%; height: 200px;"></div>

<script type="text/javascript">
    ymaps.ready(init);
    function init() {
        // Создаем карту с центром в Москве
        var myMap = new ymaps.Map("map<?=$item->id?>", {
            center: [69.50, 42.36], // Центр карты (Москва)
            zoom: 10
        });

        // Указываем адрес для геокодирования
        var address = "<?=$item->address?>";

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
