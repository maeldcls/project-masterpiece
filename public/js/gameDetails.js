var modalImg = document.querySelector("#modal-img");
var modal = document.querySelector("#modal");
document.addEventListener('DOMContentLoaded', function() {
    let readMore = document.querySelector("#readMore");
    let hide = document.querySelector("#hide");


    readMore.addEventListener('click', showMoreDetails);
    hide.addEventListener('click', hideDetails);

    function showMoreDetails() {

        document.querySelector('#summaryPreview').classList.add('hidden');
        document.querySelector('#summaryFull').classList.remove('hidden');
        document.querySelector('#summaryFull').classList.add('flex');
        readMore.classList.add('hidden');
        hide.classList.remove('hidden');
    }
    function hideDetails() {
        document.querySelector('#summaryPreview').classList.remove('hidden');
        document.querySelector('#summaryFull').classList.add('hidden');
        document.querySelector('#summaryFull').classList.remove('flex');
        readMore.classList.remove('hidden');
        hide.classList.add('hidden');
    }
});


window.onclick = function (event) {
    if (event.target === modal) {
        modal.classList.remove('fixed');
        modal.classList.add('hidden');
    }
};

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