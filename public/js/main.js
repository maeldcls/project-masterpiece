

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

    

});
selectOrder.addEventListener('change', function () {
    let value = selectOrder.value;
    console.log(value);
    // let url = new URL(window.location.href);
    // url.searchParams.set('order', value);
    // window.location.href = url.toString();
});