let modal = document.querySelector("#navResponsive");
let button = document.querySelector("#openNavResponsive");

button.addEventListener("click", (event) => {
    if(modal.classList.contains('hidden')){
        console.log("caché");
        modal.classList.remove('hidden');
        modal.classList.add('absolute');
    }else{
       console.log("soucis");
       modal.classList.remove('absolute');
        modal.classList.add('hidden');
    }
 
});

function toggleMenuVisibility() {
    const screenWidth = window.innerWidth;
  
    if (screenWidth <= 768) {
      // Show the menu if the screen width is small

    } else {
      // Hide the menu if the screen width is larger
  
    }
  }


  window.addEventListener('load', toggleMenuVisibility);

// Call the function on window resize
window.addEventListener('resize', toggleMenuVisibility);