let avatarInput = document.querySelector('.avatarModal input');
let avatarName = document.querySelector('.avatarModal .field p');

avatarInput.addEventListener('change', function() {
    if(this.files.length > 0) avatarName.textContent = this.files[0].name;
    else avatarName.textContent = 'Файл не выбран';
})