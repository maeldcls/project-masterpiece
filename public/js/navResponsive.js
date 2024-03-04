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

function toggleMenuVisibility() {
    const screenWidth = window.innerWidth;
  
    if (screenWidth <= 768) {
      // Show the menu if the screen width is small

    } else {
      // Hide the menu if the screen width is larger
  
    }
  }

  window.addEventListener('resize', () => {
    if(window.innerWidth>1023){
        modal.style.display = "none";
        darken.classList.remove('darken');
    }
  });