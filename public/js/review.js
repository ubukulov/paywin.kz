const star = document.querySelectorAll('.star');

star.forEach(elem => {
    elem.addEventListener('click', () => {
        if(elem.classList.contains('far')) {
            elem.classList.add('fas');
            elem.classList.remove('far');
        } else {
            elem.classList.add('far');
            elem.classList.remove('fas');
        }
    });
});