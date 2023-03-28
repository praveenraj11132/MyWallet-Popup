window.onload = function() {
    var popupBtn = document.getElementById("popup-btn");
    var popupContainer = document.getElementById("popup-container");
    var closeContainer = document.getElementById("close-container");

    popupBtn.onclick = function () {
        popupContainer.style.display = "block";
    };

    closeContainer.onclick = function () {
        popupContainer.style.display = "none";
    };
}
