var nav = document.getElementById("myTopnav");

function openNavbar() {
    nav.className += " responsive";
}

function closeNavbar() {
    nav.className = "topnav";
}

var sidenav = document.getElementById("sidenav");

function openSidetab() {
    sidenav.className += " sideshown";
}

function closeSidetab() {
    sidenav.className = "sidenav";
    sidenav.className += " sidemargin";
}