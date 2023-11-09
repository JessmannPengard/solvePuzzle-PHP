function showSpinner() {
    document.getElementById("spinner").classList.remove("hidden");
    document.getElementById("content").classList.add("hidden");
}

function hideSpinner() {
    document.getElementById("spinner").classList.add("hidden");
    document.getElementById("content").classList.remove("hidden");
}