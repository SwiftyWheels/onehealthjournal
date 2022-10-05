// This places the JS in strict mode which will throw errors when the code
// isn't written properly. Helps with debugging.
// This can also be placed inside a function to make the function strict mode
"use strict";

document.addEventListener("DOMContentLoaded", init);

function init(){
    const progressBars = document.querySelectorAll(".progress-bar");

    for (const progressBar of progressBars) {
        updateProgressBar(progressBar);
    }
}

function updateProgressBar(progressBar) {
    const barContainer = progressBar.querySelector(".bar-container");
    const bar = barContainer.querySelector(".bar");
    const percent = barContainer.querySelector("span");
    bar.style.width = percent.innerText;
}

