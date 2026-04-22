// MENU
function toggleMenu() {
    const menu = document.querySelector("#menu-mobile");

    if (menu.style.display === "flex") {
        menu.style.display = "none";
    } else {
        menu.style.display = "flex";
    }
}

function chiudiMenu() {
    document.querySelector("#menu-mobile").style.display = "none";
}

