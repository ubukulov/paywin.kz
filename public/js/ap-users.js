// Tabs

const tabs = document.querySelectorAll('.ap-users__tabheader-item'),
        tabsContent = document.querySelectorAll('.ap-users__tabcontent'),
        tabsParent = document.querySelector('.ap-users__tabheader-items');

function hideTabContent() {
    tabsContent.forEach(item => {
        item.classList.add('np-hide');
        item.classList.remove('np-show', 'np-fade');
    });

    tabs.forEach(item => {
        item.classList.remove('ap-users__tabheader-item_active');
    });
}

function showTabContent(i = 0) {
    tabsContent[i].classList.add('np-show', 'np-fade');
    tabsContent[i].classList.remove('np-hide');

    tabs[i].classList.add('ap-users__tabheader-item_active');
}

hideTabContent();
showTabContent();

tabsParent.addEventListener('click', (event) => {
    const target = event.target;

    if(target && target.classList.contains('ap-users__tabheader-item')) {
        tabs.forEach((item, i) => {
            if(target == item) {
                hideTabContent();
                showTabContent(i);
            }
        });
    }
});