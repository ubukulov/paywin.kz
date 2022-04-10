// upload

const contractUpload = document.querySelector('#upload-contract'),
      contractUploadBtn = document.querySelector('.partner__right-upload-contract'),
      uploadCard = document.querySelector('#upload-card'),
      uploadCardBtn = document.querySelector('.partner__requisites-card-upload'),
      uploadDescr = document.querySelector('#upload-descr'),
      uploadDescrBtn = document.querySelector('.partner__descr-upload-btn'),
      uploadAddress = document.querySelector('#upload-address'),
      uploadAddressBtn = document.querySelector('.partner__address-upload-btn');


contractUploadBtn.addEventListener('click', () => {
    contractUpload.click();
});

uploadCardBtn.addEventListener('click', () => {
    uploadCard.click();
});

uploadDescrBtn.addEventListener('click', () => {
    uploadDescr.click();
});

uploadAddressBtn.addEventListener('click', () => {
    uploadAddress.click();
});


// showMoreText

const openText = document.querySelector('.open-text'),
      moreText = document.querySelector('.more-text'),
      dots = document.querySelector('.dots'),
      closeText = document.querySelector('.close-text');

openText.addEventListener('click', (e) => {
    e.preventDefault();
    moreText.style.display = 'inline';
    dots.style.display = 'none';
    openText.style.display = 'none';
    closeText.style.display = 'block';
});

closeText.addEventListener('click', (e) => {
    e.preventDefault();
    moreText.style.display = 'none';
    dots.style.display = 'inline-block';
    openText.style.display = 'inline-block';
    closeText.style.display = 'none';
});