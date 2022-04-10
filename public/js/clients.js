let checkboxes = document.querySelectorAll('.mark-input');
let checkAllOption = document.querySelector('.option-all-input');
let checkedItem = document.querySelectorAll('.checked__item');
let count = 0;

document.querySelector('#total').innerHTML = checkedItem.length;

function checkAll(myCheckbox) {
    count = 0;
    if(myCheckbox.checked == true) {
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = true;
            count++;
            document.querySelector('#current').innerHTML = count;
        });
    } else {
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = false;
            count = 0;
            document.querySelector('#current').innerHTML = count;
        });
    }
}


checkboxes.forEach(item => {
    item.addEventListener('click', () => {
        if(item.checked == true) {
            count++;
            document.querySelector('#current').innerHTML = count;
        } else {
            count--;
            document.querySelector('#current').innerHTML = count;
        }
    });
});