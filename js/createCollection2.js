let modal = document.querySelector('.createCollectionPage .modal');
let modalBtn = document.querySelector('.chooseBooks');
let closeModal = document.querySelector('.createCollectionPage .close');

modalBtn.addEventListener('click', () => {
    modal.style.display = "flex";
})

closeModal.addEventListener('click', () => {
    modal.style.display = "none";
})