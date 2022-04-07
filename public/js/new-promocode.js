let newPromocodeSlider = new Swiper(".new-promocode__slider", {
    spaceBetween: 30,
    pagination: {
        el: ".swiper-pagination",
    },
});


// new-promocode TABS

const tabs = document.querySelectorAll('.new-promocode__tabheader-item'),
tabsContent = document.querySelectorAll('.new-promocode__tabcontent'),
tabsParent = document.querySelector('.new-promocode__tabheader-items');

function hideTabContent() {
    tabsContent.forEach(item => {
        item.classList.add('np-hide');
        item.classList.remove('np-show', 'np-fade');
    });

    tabs.forEach(item => {
        item.classList.remove('new-promocode__tabheader-item_active');
    });
}

function showTabContent(i = 0) {
    tabsContent[i].classList.add('np-show', 'np-fade');
    tabsContent[i].classList.remove('np-hide');

    tabs[i].classList.add('new-promocode__tabheader-item_active');
}

hideTabContent();
showTabContent();

tabsParent.addEventListener('click', (event) => {
const target = event.target;

    if(target && target.classList.contains('new-promocode__tabheader-item')) {
    tabs.forEach((item, i) => {
        if(target == item) {
            hideTabContent();
            showTabContent(i);
        }
    });
    }
});