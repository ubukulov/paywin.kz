<div class="partdescr__address">
    <div style="font-size: 22px;
        color: #DEDEDE;
        font-weight: 700;"><a href="{{ route('partner.imageLists') }}">Картинки ({{ $partner->images->count() }})</a></div>

    @if($partner->images->count() == 0)
        <div class="partner__address-subtitle">Вы еще не указали картинки заведения</div>
    @else
        <div class="partdescr">
            <div class="partdescr__header">
                <div class="partdescr__header-slider swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden">
                    <div class="swiper-wrapper" id="swiper-wrapper-0adf6d1010e59b0c13" aria-live="polite" style="transform: translate3d(0px, 0px, 0px);">
                        @foreach($partner->images as $image)
                            <div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 5" style="width: 480px;">
                                <img src="{{ $image->image }}" alt="photo">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination swiper-pagination-fraction swiper-pagination-horizontal"><span class="swiper-pagination-current">1</span> / <span class="swiper-pagination-total">5</span></div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                <a href="#" class="partdescr__back-btn">
                    <img src="/images/partner-description/left-arrow.svg" alt="back">
                </a>
                <a href="#" class="partdescr__share">
                    <img src="/images/partner-description/share.svg" alt="share">
                </a>
                <a href="#" class="partdescr__camera">
                    <img src="/images/partner-description/camera-icon.svg" alt="camera">
                </a>
                <div class="partdescr__rating-block">
                    <div class="partdescr__rating-num">4.2</div>
                    <div class="partdescr__rating-stars">
                        <i class="fas fa-star full"></i>
                        <i class="fas fa-star full"></i>
                        <i class="fas fa-star full"></i>
                        <i class="fas fa-star full"></i>
                        <i class="fas fa-star empty"></i>
                    </div>
                    <div class="partdescr__rating-score">
                        <span>350</span>
                        оценок
                    </div>
                </div>
            </div>
        </div>

    @endif

    @if(Auth::check() && Auth::user()->user_type == 'partner' && Route::currentRouteName() == 'partner.cabinet')
        <a href="{{ route('partner.imageCreate') }}" class="partner__address-upload-btn">+ добавить картинки</a>
    @endif
</div>
