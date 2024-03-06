let modal = document.querySelector("#navResponsive");
let button = document.querySelector("#openNavResponsive");
let buttonClose = document.querySelector("#close");
let body = document.querySelector("body");
let darken = document.querySelector('#darken');



button.addEventListener("click", (event) => {
  modal.style.display ="block";
  darken.classList.add('darken');
});

buttonClose.addEventListener("click", (event) => {
 
  modal.style.display ="none";
  darken.classList.remove('darken');
});

darken.addEventListener("click", (event) => {
 
  modal.style.display ="none";
  darken.classList.remove('darken');
});

  window.addEventListener('resize', () => {
    if(window.innerWidth>1023){
       
        modal.style.display = "none";
        darken.classList.remove('darken');
    }
  });