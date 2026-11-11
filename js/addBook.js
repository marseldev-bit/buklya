let imgInput = document.querySelector('.createBookPage .image input');
let imgName = document.querySelector('.createBookPage .imageInput p');

imgInput.addEventListener('change', function() {
    if(this.files.length > 0) imgName.textContent = this.files[0].name;
    else imgName.textContent = 'Файл не выбран';
})