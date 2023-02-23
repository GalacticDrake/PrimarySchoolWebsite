var select = document.getElementById("view");
var tabs = document.getElementsByClassName("tabs");
var visible = document.getElementById("visible");
var vicon = document.getElementById("v-icon");
var vlabel = document.getElementById("v-text");
var vdesc = document.getElementById("v-desc");
var deletelbl = document.getElementById("deletelbl");
var deletebtn = document.getElementById("delete");
var ddesc = document.getElementById("d-desc");
var closestat = document.getElementsByClassName("status");

function changeVisible() {
    if(visible.checked == false) {
        vicon.innerText = "visibility";
        vlabel.innerText = "Show";
        vdesc.innerText = "This document is hidden from students.";
    } else {
        vicon.innerText = "visibility_off";
        vlabel.innerText = "Hide";
        vdesc.innerText = "This document is visible to students.";
    }
}

function confirmDelete() {
    if(deletebtn.checked == true) {
        deletelbl.classList.add("warn-label");
        ddesc.innerText = "This document has been flagged for deletion.";
    } else {
        deletelbl.classList.remove("warn-label");
        ddesc.innerText = "";
    }
}

function changeView() {
    var selected = select.options[select.selectedIndex].value;  

    if(selected == "grid") {
        for(var i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove("tabs-list");
            tabs[i].classList.add("tabs-grid");
        }
    }

    else if (selected == "list") {
        for(var i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove("tabs-grid");
            tabs[i].classList.add("tabs-list");
        }
    }
}

function closeStatus() {
    for(var i = 0; i < closestat.length; i++) {
        closestat[i].style.display = "none";
    }
}

// for cancel button
function goBack() {
    window.history.back();
}