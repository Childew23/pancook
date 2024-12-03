// JS library for animation
import anime from '../animejs/lib/anime.es.js';

document.addEventListener('DOMContentLoaded', () => {
    // Animation for the title
    anime({
        targets: 'h1, h5, h6',
        translateX: 2000,
        direction: 'reverse',
        duration: 700,
        easing: 'easeInOutSine'
    });

    // Animation for articles
    anime({
        targets: '.post-preview',
        translateX: 500,
        direction: 'reverse',
        easing: 'easeInOutQuad',
        delay: anime.stagger(500, {direction: 'reverse', easing: 'easeOutQuad'})
      });
});