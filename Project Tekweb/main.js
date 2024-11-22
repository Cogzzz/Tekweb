// main.js
const searchIcon = document.getElementById("search-icon");
const searchBox = document.querySelector(".search-box");

// Tambahkan event listener untuk icon search
searchIcon.addEventListener("click", () => {
    // Toggle tampilan search box
    if (searchBox.style.display === "none" || searchBox.style.display === "") {
        searchBox.style.display = "block";
    } else {
        searchBox.style.display = "none";
    }
});
