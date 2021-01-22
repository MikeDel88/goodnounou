document.addEventListener('DOMContentLoaded', function () {

    /**
     * Déclencheur sur le burger du menu en mobile
     */
    let menuMobile = document.querySelector('#nav-mobile');
    let menuBurger = document.querySelector('#burger');
    menuBurger.addEventListener('click', () => {

        menuMobile.classList.toggle('active');
        let value = 0;
        menuMobile.style.opacity = value;

        if (menuMobile.classList.contains('active')) {
            menuBurger.firstChild.classList.remove('fa-bars');
            menuBurger.firstChild.classList.add('fa-times');
            /**
             * Effet de fade sur l'ouverture du menu
             */
            let fadeIn = setInterval(() => {
                value += 0.1;
                menuMobile.style.opacity = value;
                if (value >= 1) {
                    clearInterval(fadeIn)
                }
            }, 50);
        } else {
            menuBurger.firstChild.classList.remove('fa-times');
            menuBurger.firstChild.classList.add('fa-bars');
        }
    })

    /**
     * Si le menu mobile est active alors je crée un déclencheur sur le main pour le désactiver
     */
    let main = document.querySelector('main');
    main.addEventListener('click', () => {
        if (menuMobile.classList.contains('active')) {
            menuMobile.classList.remove('active');
            menuBurger.firstChild.classList.remove('fa-times');
            menuBurger.firstChild.classList.add('fa-bars');
        }
    })
})