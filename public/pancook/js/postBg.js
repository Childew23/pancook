document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('.header-post');
    const container = document.querySelector('.container');
    const image = container.getAttribute('data-image'); //Retrieve data-image

    function postBackground() {
        header.style.backgroundImage = `url(${image})`;
    }

    postBackground();

});
