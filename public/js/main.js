document.addEventListener('DOMContentLoaded', function () {
    let screenshotsContainers = document.querySelectorAll('.screenshots');
    

   
    screenshotsContainers.forEach(function (screenshotsContainer) {
        let currentIndex = 0;
        let intervalId;
        let screenshotImage = screenshotsContainer.querySelector('.screenshot-image');
        let screenshots = JSON.parse(screenshotsContainer.getAttribute('data-game-screenshots'));

        function changeImage() {
            currentIndex = (currentIndex + 1) % screenshots.length;
            screenshotImage.src = screenshots[currentIndex];
        }

        function startInterval() {
            intervalId = setInterval(changeImage, 1000);
        }

        function stopInterval() {
            clearInterval(intervalId);
        }

        screenshotsContainer.addEventListener('mouseenter', function () {
            startInterval();
        });

        screenshotsContainer.addEventListener('mouseleave', function () {
            stopInterval();
            screenshotImage.src = screenshots[0];
        });

    });
    // document.addEventListener('DOMContentLoaded', function () {
    //     var targetDiv = document.getElementById('screenshotImage');
    //     var cursorPosition = document.getElementById('cursorPosition');

    //     targetDiv.addEventListener('mousemove', function (event) {
    //         var x = event.clientX - targetDiv.getBoundingClientRect().left;
    //         var y = event.clientY - targetDiv.getBoundingClientRect().top;

    //         // Mettez à jour la position du curseur
    //         cursorPosition.textContent = 'X: ' + x + ', Y: ' + y;

    //         // Votre logique ici en fonction de la position du curseur
    //         // Par exemple, vous pouvez déclencher un événement ou effectuer une action spécifique
    //         if (x > 150 && y > 100) {
    //             // Faites quelque chose lorsque le curseur est dans une certaine position
    //             console.log('Le curseur est dans une certaine position.');
    //         }
    //     });
    // });


    // Au chargement de la page, commencer l'intervalle

});