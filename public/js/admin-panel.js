var apSlider = new Swiper(".admin-panel__slider", {
    spaceBetween: 20,
    pagination: {
      el: ".swiper-pagination",
    },
});


// Tabs

const tabs = document.querySelectorAll('.admin-panel__tabheader-item'),
        tabsContent = document.querySelectorAll('.admin-panel__tabcontent'),
        tabsParent = document.querySelector('.admin-panel__tabheader-items');

function hideTabContent() {
    tabsContent.forEach(item => {
        item.classList.add('np-hide');
        item.classList.remove('np-show', 'np-fade');
    });

    tabs.forEach(item => {
        item.classList.remove('admin-panel__tabheader-item_active');
    });
}

function showTabContent(i = 0) {
    tabsContent[i].classList.add('np-show', 'np-fade');
    tabsContent[i].classList.remove('np-hide');

    tabs[i].classList.add('admin-panel__tabheader-item_active');
}

hideTabContent();
showTabContent();

tabsParent.addEventListener('click', (event) => {
    const target = event.target;

    if(target && target.classList.contains('admin-panel__tabheader-item')) {
        tabs.forEach((item, i) => {
            if(target == item) {
                hideTabContent();
                showTabContent(i);
            }
        });
    }
});