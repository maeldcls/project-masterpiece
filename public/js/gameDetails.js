var modalImg = document.querySelector("#modal-img");
let modal1 = document.querySelector("#modal1");

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
    if (event.target === modal1) {
        modal1.classList.remove('fixed');
        modal1.classList.add('hidden');
    }
};

 function showModal(src) {
    modal1.classList.remove('hidden');
    modal1.classList.add('fixed');
    modalImg.src = src;
}

function closeModal() {
    modal1.classList.add('hidden');
}