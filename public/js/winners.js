var swiper = new Swiper(".winners__top-slider-wrapper", {
    spaceBetween: 15,
    slidesPerView: 3,
    navigation: {
      nextEl: ".winners__top-slider-next",
      prevEl: ".winners__top-slider-prev",
    },
    breakpoints: {
        320: {
          spaceBetween: 0,
        },
        375: {
            spaceBetween: 15,
        },
      },
});
var swiper2 = new Swiper(".winners__bottom-slider-wrapper", {
    spaceBetween: 55,
    slidesPerView: 2,
    navigation: {
      nextEl: ".winners__bottom-slider-next",
      prevEl: ".winners__bottom-slider-prev",
    },
});