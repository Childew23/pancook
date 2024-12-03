import anime from '../animejs/lib/anime.es.js';

document.addEventListener('DOMContentLoaded', () => {
    anime({
        targets: 'h1, h5, h6',
        translateX: 2000,
        direction: 'reverse',
        duration: 700,
        easing: 'easeInOutSine'
    });
});