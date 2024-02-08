var modal = document.querySelector("#modal");

// Get the modal image tag
var modalImg = document.querySelector("#modal-img");

// this function is called when a small image is clicked
function showModal(src) {
    modal.classList.remove('hidden');
    modal.classList.add('fixed');
    modalImg.src = src;
}

// this function is called when the close button is clicked
function closeModal() {
    modal.classList.add('hidden');
}

window.onclick = function (event) {
    console.log("oui")
    if (event.target === modal) {
        modal.classList.remove('fixed');
        modal.classList.add('hidden');
    }
};