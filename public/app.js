import Like from "./pancook/js/like.js";

document.addEventListener('DOMContentLoaded', () => {
    const likeElements = [].slice.call(document.querySelectorAll('a[data-action="like"]')); //Retrieve and convert data-action into an array
    if (likeElements) {
        new Like(likeElements);
    }
})
