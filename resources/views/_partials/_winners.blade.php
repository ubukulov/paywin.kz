<div class="winners" style="margin-top: 0px; padding-top: 0px; height: auto;">
    <div class="winners__wrapper" style="padding-top: 20px; margin-top: 0px;">
        <div class="winners__top-slider">
            <div class="winners__top-slider-title">
                Выиграли призов: <p><span>{{ count($winners) }}</span> за месяц</p>
            </div>
            <div class="winners__top-slider-wrapper">
                <div class="swiper-wrapper">
                    <div class="winners__top-item swiper-slide">
                        <div class="winners__top-avatar">
                            <img src="/images/winners/avatar.png" alt="">
                        </div>
                        <div class="winners__top-info">
                            {{ $winner->full_name }} выиграл приз
                        </div>

                        <div class="winners__top-card @if($winner->share_type == 'share') purple @elseif($winner->share_type == 'cashback') red @else blue @endif">
                            {{ $winner->share_title }}
                        </div>

                    </div>

                </div>
            </div>
            <div class="winners__top-slider-next"><img src="/images/winners/next-arrow.svg" alt=""></div>
            <div class="winners__top-slider-prev"><img src="/images/winners/prev-arrow.svg" alt=""></div>
        </div>
        <div class="winners__bottom-slider">
            <div class="winners__bottom-slider-title">TOP партнеров</div>
            <div class="winners__bottom-slider-wrapper">
                <div class="swiper-wrapper">
                    <div class="winners__bottom-item swiper-slide">
                        <div class="winners__bottom-logo">
                            <img src="/images/winners/kfc-logo.png" alt="">
                        </div>
                        <div class="winners__bottom-name">KFC</div>
                        <div class="winners__bottom-info">
                            вручили призов: <p><span>1280</span> за день</p>
                        </div>
                    </div>
                    <div class="winners__bottom-item swiper-slide">
                        <div class="winners__bottom-logo">
                            <img src="/images/winners/papa-johns-logo.png" alt="">
                        </div>
                        <div class="winners__bottom-name">Papa John’s</div>
                        <div class="winners__bottom-info">
                            вручили призов: <p><span>1280</span> за день</p>
                        </div>
                    </div>
                    <div class="winners__bottom-item swiper-slide">
                        <div class="winners__bottom-logo">
                            <img src="/images/winners/kfc-logo.png" alt="">
                        </div>
                        <div class="winners__bottom-name">KFC</div>
                        <div class="winners__bottom-info">
                            вручили призов: <p><span>1280</span> за день</p>
                        </div>
                    </div>
                    <div class="winners__bottom-item swiper-slide">
                        <div class="winners__bottom-logo">
                            <img src="/images/winners/papa-johns-logo.png" alt="">
                        </div>
                        <div class="winners__bottom-name">Papa John’s</div>
                        <div class="winners__bottom-info">
                            вручили призов: <p><span>1280</span> за день</p>
                        </div>
                    </div>
                    <div class="winners__bottom-item swiper-slide">
                        <div class="winners__bottom-logo">
                            <img src="/images/winners/kfc-logo.png" alt="">
                        </div>
                        <div class="winners__bottom-name">KFC</div>
                        <div class="winners__bottom-info">
                            вручили призов: <p><span>1280</span> за день</p>
                        </div>
                    </div>
                    <div class="winners__bottom-item swiper-slide">
                        <div class="winners__bottom-logo">
                            <img src="/images/winners/papa-johns-logo.png" alt="">
                        </div>
                        <div class="winners__bottom-name">Papa John’s</div>
                        <div class="winners__bottom-info">
                            вручили призов: <p><span>1280</span> за день</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="winners__bottom-slider-next"><img src="/images/winners/next-arrow.svg" alt=""></div>
            <div class="winners__bottom-slider-prev"><img src="/images/winners/prev-arrow.svg" alt=""></div>
        </div>
    </div>
</div>
