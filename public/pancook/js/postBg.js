document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.header-post');
    const container = document.querySelector('.container');
    const images = JSON.parse(container.getAttribute('data-images'));

    function postBackground() {
        header.style.backgroundImage = `url(${images[0]})`;
    }

    postBackground();

});
