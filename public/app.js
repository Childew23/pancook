import Like from "./pancook/js/like.js";

document.addEventListener('DOMContentLoaded', () => {
    const likeElements = [].slice.call(document.querySelectorAll('a[data-action="like"]')); //Retrieve and convert data-action into an array
    if (likeElements) {
        new Like(likeElements);
    }
})

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
        .then((registration) => {
            console.log('Service Worker enregistré avec succès : ', registration);
        })
        .catch((error) => {
            console.log('Erreur lors de l\'enregistrement du Service Worker : ', error);
        });
    });
}