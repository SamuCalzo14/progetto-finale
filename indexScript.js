/* MENU MOBILE */

function toggleMenu() {
    var menu = document.getElementById("menu-mobile");

    if (menu.style.display === "flex") {
        menu.style.display = "none";
    } else {
        menu.style.display = "flex";
    }
}

function chiudiMenu() {
    document.getElementById("menu-mobile").style.display = "none";
}