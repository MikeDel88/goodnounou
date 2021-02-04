let mainMenu = document.querySelector('.menu_principal_mobile');
let secondaryMenu = document.querySelector('.menu_secondaire_mobile');
let fond = document.querySelector('.fond');

mainMenu.addEventListener('click', function () {
    let menu = document.querySelector('aside');
    menu.style.display = 'block';
    menu.style.position = 'fixed';
    menu.style.top = '0';
    menu.style.width = '70%';
    menu.style.bottom = '0';
    menu.style.boxShadow = '2px 1px 5px rgb(0 0 0 / 25%)'
    document.querySelector('.fond').style.display = 'block';
})

fond.addEventListener('click', function () {
    let menu = document.querySelector('aside');
    let menu2 = document.querySelector('main header nav');
    menu.style.display = 'none';
    menu2.style.display = 'none';
    this.style.display = 'none'
})

secondaryMenu.addEventListener('click', function () {
    document.querySelector('.fond').style.display = 'block';
    let menu = document.querySelector('main header nav');
    menu.style.display = 'flex';
    menu.style.justifyContent = 'center'
    menu.style.alignItems = 'center'
    menu.style.position = 'fixed';
    menu.style.top = "0"
    menu.style.left = "0";
    menu.style.right = "0";
    menu.style.minHeight = "120px"
    menu.style.zIndex = '100';
    menu.style.backgroundColor = "white"
})