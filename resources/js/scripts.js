// This places the JS in strict mode which will throw errors when the code
// isn't written properly. Helps with debugging.
// This can also be placed inside a function to make the function strict mode
"use strict";
import * as toggleHelper from './utilities/toggleHelpers.js';

document.addEventListener("DOMContentLoaded", init);

function init() {
    setAllDateInputsToCurrentDate();
    addListenersToButtons();
}

function addListenersToButtons() {
    // Get all the buttons
    let workoutButton = document.getElementById("workoutButton");
    let caloriesButton = document.getElementById("caloriesButton");
    let weightButton = document.getElementById("weightButton");

    let workoutButtonNav = document.getElementById("workoutButtonNav");
    let caloriesButtonNav = document.getElementById("caloriesButtonNav");
    let weightButtonNav = document.getElementById("weightButtonNav");

    // Get all the containers
    let workoutContainer = document.getElementById("containerWorkout");
    let caloriesContainer = document.getElementById("containerCalories");
    let weightContainer = document.getElementById("containerWeight");

    // Nav containers/buttons
    let navButtonsContainer = document.querySelector(".container-nav" +
        " .nav-dropdown-container");

    // Date Input
    let dateInput = document.querySelector("input[type='date']");

    // Add appropriate event listeners
    workoutButton.addEventListener("click", function () {
        toggleHelper.toggleHiddenOnOtherElements(workoutContainer,
            [
                caloriesContainer,
                weightContainer
            ], "hidden")
    });

    caloriesButton.addEventListener("click", function () {
        toggleHelper.toggleHiddenOnOtherElements(caloriesContainer,
            [
                workoutContainer,
                weightContainer
            ], "hidden")
    });

    weightButton.addEventListener("click", function () {
        toggleHelper.toggleHiddenOnOtherElements(weightContainer,
            [
                workoutContainer,
                caloriesContainer
            ], "hidden")
    });

    // Add appropriate event listeners
    workoutButtonNav.addEventListener("click", function () {
        toggleHelper.toggleHiddenOnOtherElements(workoutContainer,
            [
                caloriesContainer,
                weightContainer
            ], "hidden")
    });

    caloriesButtonNav.addEventListener("click", function () {
        toggleHelper.toggleHiddenOnOtherElements(caloriesContainer,
            [
                workoutContainer,
                weightContainer
            ], "hidden")
    });

    weightButtonNav.addEventListener("click", function () {
        toggleHelper.toggleHiddenOnOtherElements(weightContainer,
            [
                workoutContainer,
                caloriesContainer
            ], "hidden")
    });

    navButtonsContainer.addEventListener("click", function () {
        let navDropDownBars = navButtonsContainer.querySelectorAll(".nav-dropdown-bar");
        let navDropDownLinks = navButtonsContainer.querySelector(".nav-dropdown-links");
        toggleHelper.toggleClassOnElements(navDropDownBars, "toggle-on");
        toggleHelper.toggleClassOnElement(navDropDownLinks, "hidden");
    })

    dateInput.addEventListener("keydown", function (e) {
        e.preventDefault();
    })

}

/**
 * Sets all date inputs on the DOM to have a value of the current date. It
 * also sets the max value of the input to be the current date. This max
 * value will prevent choosing a future date.
 */
function setAllDateInputsToCurrentDate() {
    // Select all date inputs on the DOM
    let dateInputs = document.querySelectorAll("input[type='date']");
    // Create a new date object
    let date = new Date();
    // Get the month, add 1 as it's 0-11, if it's greater than 12 than set
    // it to 1 for january
    let month = date.getMonth() > 12 ? 1 : date.getMonth() + 1;
    // Get the day
    let day = date.getDate();
    // Get the year
    let year = date.getFullYear();

    // Add 0 to the month as it needs to conform to the mm format
    if (month < 10) {
        month = "0" + month;
    }
    
    if (day < 10){
        day = "0" + day;
    }

    // Set the date in a "yyyy-mm-dd" format
    let dateValue = year.toString() + "-" + month.toString() + "-" + day.toString();

    // Update all date inputs to have the current day as it's value and max
    // value
    for (const dateInput of dateInputs) {
        dateInput.setAttribute("value", dateValue);
        dateInput.setAttribute("max", dateValue);
    }
}