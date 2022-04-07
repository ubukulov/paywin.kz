// Tabs

const tabs = document.querySelectorAll('.ap-agents__tabheader-item'),
        tabsContent = document.querySelectorAll('.ap-agents__tabcontent'),
        tabsParent = document.querySelector('.ap-agents__tabheader-items');

function hideTabContent() {
    tabsContent.forEach(item => {
        item.classList.add('np-hide');
        item.classList.remove('np-show', 'np-fade');
    });

    tabs.forEach(item => {
        item.classList.remove('ap-agents__tabheader-item_active');
    });
}

function showTabContent(i = 0) {
    tabsContent[i].classList.add('np-show', 'np-fade');
    tabsContent[i].classList.remove('np-hide');

    tabs[i].classList.add('ap-agents__tabheader-item_active');
}

hideTabContent();
showTabContent();

tabsParent.addEventListener('click', (event) => {
    const target = event.target;

    if(target && target.classList.contains('ap-agents__tabheader-item')) {
        tabs.forEach((item, i) => {
            if(target == item) {
                hideTabContent();
                showTabContent(i);
            }
        });
    }
});


// btn 
const btn = document.querySelectorAll('.ap-agents__btn');

btn.forEach(item => {
    item.addEventListener('click', () => {
        item.classList.toggle('is-active');
    });
});