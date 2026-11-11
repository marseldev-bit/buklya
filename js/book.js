'use strict';

const ratingContainer = document.querySelector('.reviewModal .rating');
const stars = document.querySelectorAll('.reviewModal .rating svg');
const starInput = document.querySelector('.reviewModal .rating input');
let selectedRating = 0;

if(starInput.value) {
    stars.forEach((star, i) => {
        if(i < starInput.value) star.classList.add('active');
    })
    selectedRating = starInput.value;
}

stars.forEach((star, i) => {
    star.addEventListener('mouseenter', () => {
        stars.forEach((s, j) => {
            if (i >= j) s.classList.add('active');
            else s.classList.remove('active');
        });
    });

    star.addEventListener('click', () => {
        selectedRating = i + 1;
        
        stars.forEach((s, j) => {
            if (i >= j) s.classList.add('active');
            else s.classList.remove('active');
        });

        if (starInput) starInput.value = selectedRating;
    });
});

ratingContainer.addEventListener('mouseleave', () => {
    stars.forEach((s, j) => {
        if (selectedRating > 0 && j < selectedRating) {
            s.classList.add('active');
        } else {
            s.classList.remove('active');
        }
    });
});