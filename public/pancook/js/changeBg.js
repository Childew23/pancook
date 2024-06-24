document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('header');
    const container = document.querySelector('.container');
    const images = JSON.parse(container.getAttribute('data-images')); //Retrieve and convert data-images into an array
    
    let currentIndex = 0;

    function changeBackground() {
        header.style.backgroundImage = `url(${images[currentIndex]})`;
        currentIndex = (currentIndex + 1) % images.length;
    }

    if (images.length > 0) {
        changeBackground();
        setInterval(changeBackground, 3000);
    }
});
