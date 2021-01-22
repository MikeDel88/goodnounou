/**
 * Ajout des animations dans le DOM
 */
let animationIn = () => {
    document.querySelector('header').classList.add('animation-menu');
    document.querySelector('#parents h2').classList.add('animation-titre-parents');
    document.querySelector('#assistante-maternelle h2').classList.add('animation-titre-ass-mat');
    document.querySelector('#to-top').classList.add('animation-to-top');
}
/**
 * Retire des animations dans le DOM
 */
let animationOut = () => {
    document.querySelector('header').classList.remove('animation-menu');
    document.querySelector('#parents h2').classList.remove('animation-titre-parents');
    document.querySelector('#assistante-maternelle h2').classList.remove('animation-titre-ass-mat');
    document.querySelector('#to-top').classList.remove('animation-to-top');
}

/**
 * Si le visiteur est déjà en session de visite, on annule les animations de bienvenue
 */
if (!sessionStorage.getItem('visiteur')) {
    sessionStorage.setItem('visiteur', 'navigation');
    animationIn();
} else {
    animationOut();
}

