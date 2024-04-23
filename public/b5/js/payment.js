$(document).ready(function(){
  $('.slider').slick({
    dots: true,
    arrows: false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    adaptiveHeight: false
  });

    $('.switch-btn').click(function(){
        $(this).toggleClass('switch-on'); return false;
    });

    $('#balance_switch').toggle(function(){
        $('#balance').val(1);
    }, function(){
        $('#balance').val(0);
    });
});
