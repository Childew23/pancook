document.addEventListener('DOMContentLoaded', function () {
    const header = document.querySelector('.header-post');
    const container = document.querySelector('.container');
    const image = container.getAttribute('data-image'); //Retrieve data-image

    function postBackground() {
        if (image) {
            header.style.backgroundImage = `url(/pancook/img/posts/${image})`;
        }else{
            header.style.backgroundImage = `url(https://www.allrecipes.com/thmb/WqWggh6NwG-r8PoeA3OfW908FUY=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/21014-Good-old-Fashioned-Pancakes-mfs_001-1fa26bcdedc345f182537d95b6cf92d8.jpg)`;
        }
    }

    postBackground();
});
