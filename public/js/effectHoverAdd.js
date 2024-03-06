let addedElements = document.querySelectorAll('.added');
addedElements.forEach(function (element) {
    let addedText = element.querySelector('.added-text');
    element.addEventListener('mouseover', function () {
        onHoverAdded(addedText);
    });

    element.addEventListener('mouseout', function () {
        onLeaveAdded(addedText);
    });
    
});

function onHoverAdded(addedText) {
    addedText.textContent = "REMOVE";
    addedText.classList.add('black');
}
function onLeaveAdded(addedText) {
    addedText.textContent = "ADDED";
    addedText.classList.remove('black');
    addedText.classList.add('white');
}