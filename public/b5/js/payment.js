$(document).ready(function(){
  $('.slider').slick({
    dots: true,
    arrows: false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    adaptiveHeight: false
  });
});

$(document).ready(function(){
	$('.switch-btn').click(function(){
    $(this).toggleClass('switch-on'); return false;
	});
});
