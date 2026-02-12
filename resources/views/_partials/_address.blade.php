<script src="https://api-maps.yandex.ru/2.1/?apikey=<?= env('YANDEX_API_KEY') ?>&lang=ru_RU" type="text/javascript"></script>

<div class="partdescr__address space-y-4">
    <div style="color: #dedede; font-size: 22px; font-weight: bold;" class="text-lg font-semibold">Адреса ({{ $partner->address->count() }})</div>

    @if($partner->address->count() == 0)
        <div class="text-gray-500">Вы еще не указали адрес заведения</div>
    @else
        @php
            $arr = [];
            foreach($partner->address as $item){
                $arr[$item->id] = $item->toArray();
                $arr[$item->id]['address'] = str_replace('<br>', '', $item->address);
            }
            $addresses = $arr;
        @endphp

        <div class="yanmaps relative w-full overflow-hidden">
            <!-- Слайдер -->
            <div class="slider-container flex transition-transform duration-500 ease-in-out w-full">
                @foreach($addresses as $item)
                    <div class="yanmaps-item min-w-full text-center bg-gray-100 border border-gray-300">
                        <div id="map{{$item['id']}}" class="w-full h-52 mb-2"></div>
                        <div class="text-sm text-gray-700">{{ $item['address'] }}</div>
                    </div>

                    <script type="text/javascript">
                        ymaps.ready(init);
                        function init() {
                            var myMap = new ymaps.Map("map<?=$item['id']?>", {
                                center: [69.50, 42.36],
                                zoom: 2
                            });
                            var address = "<?=$item['address']?>";
                            ymaps.geocode(address).then(function (res) {
                                var firstGeoObject = res.geoObjects.get(0);
                                var coords = firstGeoObject.geometry.getCoordinates();
                                var placemark = new ymaps.Placemark(coords, { balloonContent: 'Адрес: ' + address });
                                myMap.setCenter(coords, 16);
                                myMap.geoObjects.add(placemark);
                            });
                        }
                    </script>
                @endforeach
            </div>

            <!-- Навигационные точки -->
            <div class="dots-container flex justify-center mt-4 space-x-2">
                @foreach($addresses as $key=>$val)
                    <span class="dot w-3 h-3 rounded-full bg-gray-400 cursor-pointer" data-slide="{{$key}}"></span>
                @endforeach
            </div>
        </div>

        <script>
            const slider = document.querySelector('.slider-container');
            const dots = document.querySelectorAll('.dot');

            let currentSlide = 0;

            function goToSlide(index) {
                slider.style.transform = `translateX(-${index * 100}%)`;
                dots.forEach(dot => dot.classList.remove('bg-gray-700'));
                dots[index].classList.add('bg-gray-700');
                currentSlide = index;
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => goToSlide(index));
            });

            // Инициализация первой активной точки
            if(dots.length) goToSlide(0);
        </script>
    @endif

    @if(Auth::check() && Auth::user()->user_type == 'partner' && Route::currentRouteName() == 'partner.cabinet')
        <a href="{{ route('partner.addressCreate') }}"
           class="inline-block mt-2 px-3 py-1 text-xs font-semibold text-white bg-orange-500 rounded hover:bg-orange-600">
            + добавить адрес
        </a>
    @endif
</div>
