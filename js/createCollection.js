let input = document.querySelector('.createCollectionPage .image input');
let imgname = document.querySelector('.createCollectionPage .imageInput p');

input.addEventListener('change', function() {
    if(this.files.length > 0) imgname.textContent = this.files[0].name;
    else imgname.textContent = 'Файл не выбран';
})

