// This places the JS in strict mode which will throw errors when the code
// isn't written properly. Helps with debugging.
// This can also be placed inside a function to make the function strict mode
"use strict";
import * as toggleHelper from './utilities/toggleHelpers.js';

document.addEventListener("DOMContentLoaded", init);

function init(){
    addEventListenersToNav();
}

function addEventListenersToNav(){
    // Nav containers/buttons
    let navDropDownContainer = document.querySelector("nav" +
        " .nav-dropdown-container");

    navDropDownContainer.addEventListener("click", function () {
        let navDropDownBars = navDropDownContainer.querySelectorAll(".nav-dropdown-bar");
        let navDropDownLinks = navDropDownContainer.querySelector(".nav-dropdown-links");
        toggleHelper.toggleClassOnElements(navDropDownBars, "toggle-on");
        toggleHelper.toggleClassOnElement(navDropDownLinks, "hidden");
    })

}