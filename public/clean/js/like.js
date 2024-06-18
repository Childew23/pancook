export default class Like {
    constructor(likeElements) {
        this.likeElements = likeElements;

        if (this.likeElements) {
            this.init();
        }
    }

    init() {
        this.likeElements.map(element => {
            element.addEventListener('click', this.onClick.bind(this));
        })
    }

    onClick(event) {
        event.preventDefault();
        const url = event.currentTarget.href;
        const currentTarget = event.currentTarget;

        let xhr = new XMLHttpRequest;
        xhr.open("GET", url, true);
        xhr.send();

        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const res = JSON.parse(xhr.responseText);
                const nb = res.nbLike;
                const span = currentTarget.querySelector('span');

                currentTarget.dataset.nb = nb;
                span.innerHTML = nb + ' J\'aime';

                const heartFilled = currentTarget.querySelector('svg.filled');
                const heartUnfilled = currentTarget.querySelector('svg.unfilled');

                heartFilled.classList.toggle('d-none');
                heartUnfilled.classList.toggle('d-none');
            }
        }

    }
}