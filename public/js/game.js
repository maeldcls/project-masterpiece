let selectOrder = document.querySelector('#form_ordering');
let formOrdering = document.querySelector('#ordering-form-id');
let addedElements = document.querySelectorAll('.added');


selectOrder.addEventListener('change', function () {
    let value = selectOrder.value;
    formOrdering.submit();
});

function showTooltip(element) {
    var tooltip = element.nextElementSibling;
    tooltip.style.display = 'block';
}

function hideTooltip(element) {
    var tooltip = element.nextElementSibling;
    tooltip.style.display = 'none';
}
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
    addedText.classList.remove('white');
    addedText.classList.add('black');
}
function onLeaveAdded(addedText) {
    addedText.textContent = "ADDED";
    addedText.classList.remove('black');
    addedText.classList.add('white');
}