

var sidebar = document.getElementById("explore");
var article = document.getElementById("article");
var btnAside = document.getElementById("toggle-aside");
var btnInc = document.getElementById("font-inc");
var btnDec = document.getElementById("font-dec");
var btnHL = document.getElementById("toggle-highlight");
var btnTitle = document.getElementById("change-title-color");

btnAside.onclick = function() {
    var current = window.getComputedStyle(sidebar).display;
    sidebar.style.display = (current === "none") ? "flex" : "none";
};

var size = 100;
btnInc.onclick = function() {
    size += 10;
    article.style.fontSize = size + "%";
    article.style.lineHeight = (size / 100 * 1.6) + "em";
};
btnDec.onclick = function() {
    size -= 10;
    article.style.fontSize = size + "%";
    article.style.lineHeight = (size / 100 * 1.6) + "em";
};

btnHL.onclick = function() {
    var highlights = document.getElementsByClassName("highlight");
    for (var i = 0; i < highlights.length; i++) {
        var el = highlights[i];
        var currentColor = window.getComputedStyle(el).backgroundColor;
        el.style.backgroundColor = (currentColor === "rgb(240, 220, 130)") ? "" : "#f0dc82";
    }
};

btnTitle.onclick = function() {
    var h1 = document.querySelector("h1");
    h1.style.color = (h1.style.color === "black") ? "" : "black";
};

window.addEventListener("DOMContentLoaded", function() {
    alert("Welcome to Silent Storiest");
    var enteredName = prompt("What is your name?");
    if (enteredName) {
        alert("Hello " + enteredName + "!");
    } else {
        alert("You didn’t enter your name!");
    }
});

