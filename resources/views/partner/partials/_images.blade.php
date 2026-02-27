<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold">
            <a href="{{ route('partner.imageLists') }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                Картинки <span class="text-gray-300">({{ $partner->images->count() }})</span>
            </a>
        </h3>
    </div>

    @if($partner->images->count() == 0)
        <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center text-gray-500">
            Вы еще не указали картинки заведения
        </div>
    @else
        <div class="relative group">
            <div class="partdescr__header-slider swiper overflow-hidden rounded-2xl shadow-lg bg-gray-100 aspect-[4/3] md:aspect-video">
                <div class="swiper-wrapper">
                    @foreach($partner->images as $image)
                        <div class="swiper-slide h-64 md:h-96">
                            <img src="{{ $image->image }}" alt="photo" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>

                <div class="swiper-pagination !bottom-4 !left-4 !text-white !bg-black/50 !px-3 !py-1 !rounded-full !w-auto !text-xs font-bold tracking-widest"></div>

                <div class="absolute top-4 right-4 z-10 flex gap-2">
                    <a href="#" class="w-10 h-10 flex items-center justify-center bg-white/20 backdrop-blur-md hover:bg-white/40 rounded-full transition shadow-sm">
                        <img src="/images/partner-description/share.svg" alt="share" class="w-5 h-5 brightness-0 invert">
                    </a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center bg-white/20 backdrop-blur-md hover:bg-white/40 rounded-full transition shadow-sm">
                        <img src="/images/partner-description/camera-icon.svg" alt="camera" class="w-5 h-5 brightness-0 invert">
                    </a>
                </div>

                <div class="absolute bottom-4 right-4 z-10 bg-white p-3 rounded-xl shadow-xl flex flex-col items-center min-w-[100px]">
                    <div class="text-2xl font-black text-gray-800 leading-none">4.2</div>
                    <div class="flex text-[10px] text-yellow-400 my-1">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star text-gray-200"></i>
                    </div>
                    <div class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter text-center leading-tight">
                        350 оценок
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(Auth::check() && Auth::user()->user_type == 'partner' && Route::currentRouteName() == 'partner.cabinet')
        <a href="{{ route('partner.imageCreate') }}"
           class="inline-flex items-center justify-center w-full md:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg active:scale-95">
            <span class="mr-2 text-xl">+</span> добавить картинки
        </a>
    @endif
</div>
