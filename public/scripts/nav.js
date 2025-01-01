const hamburger = document.querySelector('.fa-bars');
const menu = document.querySelector('nav > ul');


console.log(hamburger);
console.log(menu);


hamburger.addEventListener('click',() => {menu.classList.toggle('active')});
