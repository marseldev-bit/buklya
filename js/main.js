'use strict';

let collections = document.querySelector('.mainPage .collections');
let back = document.querySelector('.mainPage .collectionsSlide .collectionsArrow:first-child');
let next = document.querySelector('.mainPage .collectionsSlide .collectionsArrow:last-child');
let currentIndex = 0;

back.addEventListener('click', () => {
    if(currentIndex == 0) currentIndex = 3;
    else currentIndex -= 1;

    collections.style.transform = `translateX(-${currentIndex * 25}%)`;
});

next.addEventListener('click', () => {
    if(currentIndex == 3) currentIndex = 0;
    else currentIndex += 1;

    collections.style.transform = `translateX(-${currentIndex * 25}%)`;
});