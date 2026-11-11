let imageInput = document.querySelector('.addAuthorPage .image input');
let imageName = document.querySelector('.addAuthorPage .imageInput p');

imageInput.addEventListener('change', function() {
    if(this.files.length > 0) imageName.textContent = this.files[0].name;
    else imageName.textContent = 'Файл не выбран';
})