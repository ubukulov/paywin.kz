<script src="https://api-maps.yandex.ru/2.1/?apikey=<?= env('YANDEX_API_KEY') ?>&lang=ru_RU" type="text/javascript"></script>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-black text-gray-900 flex items-center gap-3">
            <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
            Адреса <span class="text-gray-300">({{ $partner->address->count() }})</span>
        </h3>
    </div>

    @if($partner->address->count() == 0)
        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-[2rem] p-12 text-center">
            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-gray-300 mx-auto mb-4">
                <i class="fas fa-map-marked-alt text-2xl"></i>
            </div>
            <p class="text-gray-400 font-medium">Вы еще не указали адреса вашего заведения</p>
        </div>
    @else
        @php
            $addresses = $partner->address->map(function($item) {
                $item->clean_address = str_replace('<br>', ' ', $item->address);
                return $item;
            });
        @endphp

        <div class="relative group">
            <div class="partdescr__address-slider swiper rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 bg-white overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($addresses as $item)
                        <div class="swiper-slide flex flex-col">
                            <div id="map{{$item->id}}" class="w-full h-64 md:h-80 bg-gray-100"></div>

                            <div class="p-6 bg-white flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                                    <i class="fas fa-location-arrow text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Точный адрес</p>
                                    <p class="text-sm font-bold text-gray-700 leading-relaxed">{{ $item->clean_address }}</p>
                                </div>
                            </div>
                        </div>

                        <script type="text/javascript">
                            ymaps.ready(function() {
                                var myMap = new ymaps.Map("map{{$item->id}}", {
                                    center: [42.36, 69.50], // Дефолт
                                    zoom: 16,
                                    controls: ['zoomControl', 'fullscreenControl']
                                });

                                var address = "{{ $item->clean_address }}";
                                ymaps.geocode(address).then(function (res) {
                                    var firstGeoObject = res.geoObjects.get(0);
                                    if (firstGeoObject) {
                                        var coords = firstGeoObject.geometry.getCoordinates();
                                        var placemark = new ymaps.Placemark(coords, {
                                            balloonContent: '<strong>' + address + '</strong>'
                                        }, {
                                            preset: 'islands#emeraldDotIconWithCaption'
                                        });
                                        myMap.setCenter(coords, 16);
                                        myMap.geoObjects.add(placemark);
                                    }
                                });
                            });
                        </script>
                    @endforeach
                </div>

                <div class="absolute bottom-24 right-6 z-10 flex gap-2">
                    <button class="addr-prev w-10 h-10 rounded-xl bg-white/90 backdrop-blur shadow-md flex items-center justify-center text-gray-600 hover:bg-emerald-600 hover:text-white transition-all active:scale-90">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </button>
                    <button class="addr-next w-10 h-10 rounded-xl bg-white/90 backdrop-blur shadow-md flex items-center justify-center text-gray-600 hover:bg-emerald-600 hover:text-white transition-all active:scale-90">
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>

                <div class="swiper-pagination addr-pagination !bottom-6"></div>
            </div>
        </div>
    @endif

    @if(Auth::check() && Auth::user()->user_type == 'partner' && Route::currentRouteName() == 'partner.cabinet')
        <div class="flex justify-center md:justify-start">
            <a href="{{ route('partner.addressCreate') }}"
               class="inline-flex items-center gap-3 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-emerald-100 transition-all active:scale-95">
                <span class="text-xl leading-none">+</span>
                Добавить новый адрес
            </a>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper(".partdescr__address-slider", {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: false,
            pagination: {
                el: ".addr-pagination",
                clickable: true
            },
            navigation: {
                nextEl: ".addr-next",
                prevEl: ".addr-prev",
            },
        });
    });
</script>
