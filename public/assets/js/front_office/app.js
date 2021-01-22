/**
 * Action sur le bouton to Top qui permet de remonter vers le menu
 */
let toTop = document.querySelector('#to-top');
window.addEventListener('scroll', () => {
    (pageYOffset > innerHeight) ? toTop.classList.add('active') : toTop.classList.remove('active');
})
document.querySelector('#to-top').addEventListener('click', () => {
    window.scrollTo(0, 0);
})

/**
 * Permet de mettre en évidence la page accueil
 */
let title = document.querySelector('title').innerHTML.split('|');
let pageCurrent = title[1].trim().toLowerCase();
let current = document.querySelectorAll(`.${pageCurrent}`);
current.forEach(element => {
    element.classList.add('current');
});

/**
 * Permet de faire disparaître la balise de response  au bout de 5 secondes si un status à été envoyé
 */
let response = document.querySelector('.response')
if (response !== null) {
    setTimeout(() => {
        response.style.display = 'none';
    }, 5000)
}






