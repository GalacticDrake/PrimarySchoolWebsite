function changeRole(role) {
    document.getElementById("student-role").classList.remove("active");
    document.getElementById("teacher-role").classList.remove("active");
    document.getElementById("parent-role").classList.remove("active");
    document.getElementById("register").style.display = "none";

    switch(role) {
        case 1:
            document.getElementById("student-role").classList.add("active");
            document.getElementById("child-subtitle").innerHTML = "Hello students! You can find your textbooks, homeworks and other resources here. Login now!";
            document.getElementById("child-footer").innerHTML = "Don't remember? Ask your dad or mom, or consult us by clicking the link below.";
            break;
        case 2:
            document.getElementById("parent-role").classList.add("active");
            document.getElementById("child-subtitle").innerHTML = "Please register if you do not have an account.";
            document.getElementById("child-footer").innerHTML = "";
            document.getElementById("register").style.display = "block";
            break;
        case 3:
            document.getElementById("teacher-role").classList.add("active");
            document.getElementById("child-subtitle").innerHTML = "Please login your ID given by the administrator.";
            document.getElementById("child-footer").innerHTML = "";
        default:
    }
}


function trimText(str) {
    return str.trim().length;
}

document.getElementById("username").addEventListener("input", function() {
    if(trimText(this.value)) {
        document.getElementById("userlabel").classList.remove("label-large");
        document.getElementById("userlabel").classList.add("label-tiny");
    } else {
        document.getElementById("userlabel").classList.remove("label-tiny");
        document.getElementById("userlabel").classList.add("label-large");
    }
});

document.getElementById("password").addEventListener("input", function() {
    if(trimText(this.value)) {
        document.getElementById("pwlabel").classList.remove("label-large");
        document.getElementById("pwlabel").classList.add("label-tiny");
    } else {
        document.getElementById("pwlabel").classList.remove("label-tiny");
        document.getElementById("pwlabel").classList.add("label-large");
    }
});

document.getElementById("uid").addEventListener("input", function() {
    if(trimText(this.value)) {
        document.getElementById("uidlabel").classList.remove("label-large");
        document.getElementById("uidlabel").classList.add("label-tiny");
    } else {
        document.getElementById("uidlabel").classList.remove("label-tiny");
        document.getElementById("uidlabel").classList.add("label-large");
    }
});

document.getElementById("email").addEventListener("input", function() {
    if(trimText(this.value)) {
        document.getElementById("elabel").classList.remove("label-large");
        document.getElementById("elabel").classList.add("label-tiny");
    } else {
        document.getElementById("elabel").classList.remove("label-tiny");
        document.getElementById("elabel").classList.add("label-large");
    }
});

document.getElementById("pnumber").addEventListener("input", function() {
    if(trimText(this.value)) {
        document.getElementById("plabel").classList.remove("label-large");
        document.getElementById("plabel").classList.add("label-tiny");
    } else {
        document.getElementById("plabel").classList.remove("label-tiny");
        document.getElementById("plabel").classList.add("label-large");
    }
});