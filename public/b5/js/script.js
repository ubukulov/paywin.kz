var prize_give = document.getElementById('prize_give');
var dis_b = document.getElementById('dis_b');
var suc = document.getElementById('suc');

prize_give.addEventListener('click', function() {
    dis_b.style.display = 'none';
    suc.style.display = 'block';
});
