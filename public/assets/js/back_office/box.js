window.addEventListener('DOMContentLoaded', function () {
    let box = document.querySelectorAll('.box .close');

    box.forEach(element =>
        element.addEventListener('click', function () {

            let header = getComputedStyle(element.parentNode).height;
            let headerValue = header.split('px');
            let article = getComputedStyle(element.parentNode.parentNode).height;
            let articleValue = article.split('px');

            if (element.firstChild.classList.contains('fa-times') && articleValue[0] > 60) {
                element.firstChild.classList.remove('fa-times');
                element.firstChild.classList.add('fa-plus');
            } else {
                element.firstChild.classList.remove('fa-plus');
                element.firstChild.classList.add('fa-times');
            }

            let box = new Box(element.parentNode.parentNode, articleValue[0], headerValue[0]);
            if (articleValue[0] > 60) {
                box.reduire();
            } else {
                box.augmenter();
            }

        })
    )
})


class Box {

    heightBox;
    heightHeader;
    box;
    element;

    constructor(element, heightBox, heightHeader) {
        this.heightBox = parseInt(heightBox);
        this.box = heightBox;
        this.element = element;
        this.heightHeader = heightHeader;
    }


    reduire() {
        let that = this;

        let anim = setInterval(function () {
            if (that.heightBox <= that.heightHeader) {
                clearInterval(anim)
            } else {
                that.heightBox -= 1;
                that.element.style.height = `${that.heightBox}px`;
            }

        }, 1)
    }
    augmenter() {
        let that = this;
        let anim = setInterval(function () {
            if (that.heightBox >= 550) {
                clearInterval(anim)
            } else {
                that.heightBox += 1;
                that.element.style.height = `${that.heightBox}px`;
            }

        }, 1)
    }

}