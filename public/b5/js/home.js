$(document).ready(function(){
	$('.camera__button').click(function(){
    $('.camera--hidden').toggleClass('camera--active'); return false;
	});
});

$(document).ready(function(){
	$('.camera__button').click(function(){
    $('.camera').toggleClass('camera--high'); return false;
	});
});

$(document).ready(function(){
	$('.camera__img').click(function(){
    $('.camera__img').attr('src', 'img/icons/qr.svg');
    $('.camera__img').toggleClass('camera__img--active');
    $('.camera__img--active').attr('src', 'img/icons/qr-active.svg');
	});
});

$(document).ready(function(){
	$('.camera__img--active').click(function(){
    $('.camera__img--active').removeAttr('rel', 'img/icons/qr-active.svg');
	});
});
