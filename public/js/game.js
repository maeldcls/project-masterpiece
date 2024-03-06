let selectOrder = document.querySelector('#form_ordering');
let formOrdering = document.querySelector('#ordering-form-id');



selectOrder.addEventListener('change', function () {
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

