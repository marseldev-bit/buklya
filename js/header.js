'use strict';

let burger = document.querySelector('.burger');
let burgerLabel = document.querySelector('.burgerLabel');

burgerLabel.addEventListener('click', () => {
    burgerLabel.classList.toggle('active');
    burger.classList.toggle('active');
})