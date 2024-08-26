window.onload = function() {
    console.log('currentPage:', currentPage);
    if (currentPage === 'login' || currentPage === 'profile' || currentPage === 'reservation' || currentPage === 'admin' || currentPage === 'reservations_view' || currentPage === 'reservations') {
    }else{
        setTimeout(function() {
            document.getElementById('popup-div').style.display = 'block';
        }, 5000);
    }
};


document.addEventListener("DOMContentLoaded", function() {
    const closeButton = document.querySelector(".material-symbols-outlined");
    const popupDiv = document.getElementById("popup-div");

    closeButton.addEventListener("click", function() {
        popupDiv.style.display = "none";
    });
});
