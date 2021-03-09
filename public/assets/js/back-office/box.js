window.addEventListener('DOMContentLoaded', function () {
    let boxes = document.querySelectorAll('.box__header--close');

  boxes.forEach(element  => {

    // On Récupère la hauteur du header de la box
    let header = getComputedStyle(element.parentNode).height;
    let headerHeight = header.replace('px', '');

    // On récupère la hauteur de la box
    let article = getComputedStyle(element.parentNode.parentNode).height;
    let articleHeight = article.replace('px', '');

    // On crée un nouvel objet box, on passe en paramètre Le conteneur box, et les hauteurs
    let box = new Box(element.parentNode.parentNode, articleHeight, headerHeight);

    element.addEventListener('click', function () {

      // On récupère la hauteur de la box
      let article = getComputedStyle(this.parentNode.parentNode).height;
      let articleHeight = article.split('px');

      // On modifie l'icone en fonction de la hauteur de la box
      if (this.firstChild.classList.contains('fa-times') && parseInt(articleHeight) > parseInt(headerHeight)) {
        this.firstChild.classList.replace('fa-times', 'fa-plus');
      } else {
        this.firstChild.classList.replace('fa-plus', 'fa-times');
      }

      // On réduit ou on augmente la hauteur de la box
      if (this.firstChild.classList.contains('fa-plus')) {
        box.reduire();
      } else {
        box.augmenter();
      }
    })
  })
})


class Box {

  heightBox;
  heightHeader;
  box;
  heightBoxDefault;

  constructor(box, heightBox, heightHeader) {
    this.heightBox = parseInt(heightBox); // Hauteur de la box
    this.heightBoxDefault = parseInt(heightBox); // Hauteur de la box par default
    this.box = box; // Box
    this.heightHeader = heightHeader; // Hauteur du header de la box
  }
  reduire() {
    let that = this;
    let anim = setInterval(function () {
        if (that.heightBox <= that.heightHeader) {
            clearInterval(anim)
        } else {
            that.heightBox -= 20;
            that.box.style.height = `${that.heightBox}px`;
        }
    }, 1)
  }
  augmenter() {
    let that = this;
    let anim = setInterval(function () {
      if (that.heightBox >= that.heightBoxDefault) {
            clearInterval(anim)
      } else {
            that.heightBox += 20;
            that.box.style.height = `${that.heightBox}px`;
        }
    }, 1)
    }

}
