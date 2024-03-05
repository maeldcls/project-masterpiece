let modal = document.querySelector("#navResponsive");
let button = document.querySelector("#openNavResponsive");

let darken = document.querySelector('#darken');



button.addEventListener("click", (event) => {
    if(modal.style.display == "block"){
        modal.style.display ="none";
        darken.classList.remove('darken');
    }else{
       modal.style.display ="block";
       darken.classList.add('darken');
    }
 
});

// window.onclick = function (event) {
//     if (event.target === modal) {

//         modal.style.display ="none";
//     }
// };


  window.addEventListener('resize', () => {
    if(window.innerWidth>1023){
        modal.style.display = "none";
        darken.classList.remove('darken');
    }
  });