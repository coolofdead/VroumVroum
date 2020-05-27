window.addEventListener("DOMContentLoaded", (e)=>{
    var commandStatus = document.querySelector("#command-status");
    console.log(reviewPageUrl);
    if (commandStatus.attributes["current-status"].value != commandStatus.attributes["delivered-status"].value) {
        setInterval(reloadPage, 10000);
    }
    else {
        setTimeout(redirectToReview, 5000)
    }
});

function reloadPage() {
    location.reload(true);
}