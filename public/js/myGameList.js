// var modal = document.querySelector("#modal");
// var modalImg = document.querySelector("#modal-div");


// // function showModal(buttonElement){
// //     let url = buttonElement.getAttribute('data-url');
// //     console.log("oui");
// //     // Supprimer le formulaire existant, s'il y en a un
// //     let existingForm = modal.querySelector('form');
// //     if (existingForm) {
// //         modal.removeChild(existingForm);
// //     }

// //     // Créer un nouvel élément form
// //     let form = document.createElement('form');
// //     form.action = url; // Remplacez ceci par le chemin réel
// //     form.method = 'post';

// //     // Créer un input pour le champ comments
// //     let input = document.createElement('input');
// //     input.type = 'text';
// //     input.name = 'comments';
// //     input.placeholder = 'comments';

// //     // Ajouter l'input au formulaire
// //     form.appendChild(input);

// //     // Créer un bouton de soumission
// //     let button = document.createElement('button');
// //     button.type = 'submit';
// //     button.textContent = 'Modifier';

// //     // Ajouter le bouton au formulaire
// //     form.appendChild(button);

// //     // Ajouter le formulaire à l'élément modal
// //     modal.appendChild(form);

// //     // Afficher le modal
// //     modal.classList.remove('hidden');
// //     modal.classList.add('fixed');
    
// // }

// function showModal(buttonElement) {
//     let url = buttonElement.getAttribute('data-url');

//     // Supprimer le formulaire existant, s'il y en a un
//     let existingForm = modal.querySelector('form');
//     if (existingForm) {
//         modal.removeChild(existingForm);
//     }

//     // Faire une requête AJAX pour obtenir le formulaire
//     fetch(url, {
//         method: 'GET'
//     })
//         .then(response => response.text())
//         .then(formHtml => {
//             // Créer un div temporaire pour contenir le formulaire HTML
//             let tempDiv = document.createElement('div');
//             tempDiv.innerHTML = formHtml;

//             // Récupérer le formulaire du div temporaire
//             let form = tempDiv.querySelector('form');

//             // Insérer le formulaire dans le modal
//             modal.appendChild(form);

//             // Afficher le modal
//             modal.classList.remove('hidden');
//             modal.classList.add('fixed');
//         });
// }


// function closeModal() {
//     let form = modal.querySelector('form');
//     if (form) {
//         modal.removeChild(form);
//     }

//     // Cacher le modal
//     modal.classList.add('hidden');
//     modal.classList.remove('fixed');
// }

// window.onclick = function (event) {
//     console.log("oui")
//     if (event.target === modal) {
//         modal.classList.remove('fixed');
//         modal.classList.add('hidden');
//     }
// };