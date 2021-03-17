const MENU_MOBILE_PRINCIPAL = document.querySelector('.menu-mobile__principal');
const MENU_MOBILE_SECONDAIRE = document.querySelector('.menu-mobile__secondaire');

const MENU = {
  'principal': document.querySelector('#js-barre-navigation'),
  'secondaire': document.querySelector('#js-menu-secondaire'),
  'modal': document.querySelector('.background-modal'),
  'main' : document.querySelector('.contenu-principal'),
}

MENU_MOBILE_PRINCIPAL.addEventListener('click', function () {
  // Ouvrir la modal et le menu Principal
  MENU.principal.classList.add('is-open');
  MENU.principal.classList.add('menuPrincipalMobile');
  MENU.modal.classList.add('is-open');
  MENU.main.style.position = 'fixed';
})

MENU.modal.addEventListener('click', function () {
  // Fermer la modal, et les deux menus
  this.classList.remove('is-open');
  MENU.principal.classList.remove('is-open');
  MENU.principal.classList.remove('menuPrincipalMobile');
  MENU.secondaire.classList.remove('d-flex');
  MENU.secondaire.classList.remove('menuSecondaireMobile');
  MENU.main.style.position = 'relative';
})

MENU_MOBILE_SECONDAIRE.addEventListener('click', function () {
  // Ouvrir la modal et le menu Secondaire
  MENU.modal.classList.add('is-open');
  MENU.secondaire.classList.add('d-flex');
  MENU.secondaire.classList.add('menuSecondaireMobile');
  MENU.main.style.position = 'fixed';
})
