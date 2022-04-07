$(document).ready(function(){
	$('.keyboard__item--finger').click(function(){
    $('.popup').toggleClass('popup--active'); return false;
	});
});

$(document).ready(function(){
	$('.popup__button').click(function(){
    $('.popup').toggleClass('popup--active'); return false;
	});
});

$(document).ready(function(){
	$('.keyboard__item--finger').click(function(){
    $('.overlay').addClass('overlay--active'); return false;
	});
});

$(document).ready(function(){
	$('.popup__button').click(function(){
    $('.overlay').toggleClass('overlay--active'); return false;
	});
});
