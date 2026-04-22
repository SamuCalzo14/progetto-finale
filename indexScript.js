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
}// ========================
// MENU MOBILE
// ========================

function toggleMenu() {
    const menu = document.getElementById("menu-mobile");
    if (menu.style.display === "flex") {
        menu.style.display = "none";
    } else {
        menu.style.display = "flex";
    }
}

function chiudiMenu() {
    document.getElementById("menu-mobile").style.display = "none";
}

// Chiudi menu se si clicca fuori
document.addEventListener("click", function(e) {
    const menu  = document.getElementById("menu-mobile");
    const btn   = document.querySelector(".hamburger");
    if (!menu.contains(e.target) && !btn.contains(e.target)) {
        menu.style.display = "none";
    }
});


// ========================
// NAVBAR — sfondo al scroll
// ========================

window.addEventListener("scroll", function() {
    const nav = document.getElementById("header1");
    if (window.scrollY > 50) {
        nav.style.background = "rgba(26, 51, 40, 0.98)";
    } else {
        nav.style.background = "rgba(26, 51, 40, 0.95)";
    }
});


// ========================
// ANIMAZIONE ENTRATA SEZIONI
// ========================

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting) {
            entry.target.style.opacity    = "1";
            entry.target.style.transform  = "translateY(0)";
        }
    });
}, { threshold: 0.1 });

// Applica a tutti gli elementi da animare
document.querySelectorAll(".studio-card, .stat-item, .contatto-item").forEach(function(el) {
    el.style.opacity   = "0";
    el.style.transform = "translateY(24px)";
    el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    observer.observe(el);
});

