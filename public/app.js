import Like from "./clean/js/like.js";

document.addEventListener('DOMContentLoaded', () => {

    const likeElements = [].slice.call(document.querySelectorAll('a[data-action="like"]'));
    if (likeElements) {
        new Like(likeElements);
    }
})
