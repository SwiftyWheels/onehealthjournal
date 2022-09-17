// This places the JS in strict mode which will throw errors when the code
// isn't written properly. Helps with debugging.
// This can also be placed inside a function to make the function strict mode
"use strict";

document.addEventListener("DOMContentLoaded", init);

function init() {
    setAllDateInputsToCurrentDate();
    addTogglesToContainerButtons();
}

function addTogglesToContainerButtons() {
    // Get all the buttons
    let workoutButton = document.getElementById("workoutButton");
    let caloriesButton = document.getElementById("caloriesButton");
    let weightButton = document.getElementById("weightButton");

    // Get all the containers
    let workoutContainer = document.getElementById("containerWorkout");
    let caloriesContainer = document.getElementById("containerCalories");
    let weightContainer = document.getElementById("containerWeight");

    // Add appropriate event listeners
    workoutButton.addEventListener("click", function () {
        toggleHiddenOnOtherElements(workoutContainer,
            [
                caloriesContainer,
                weightContainer
            ], "hidden")
    });

    caloriesButton.addEventListener("click", function () {
        toggleHiddenOnOtherElements(caloriesContainer,
            [
                workoutContainer,
                weightContainer
            ], "hidden")
    });

    weightButton.addEventListener("click", function () {
        toggleHiddenOnOtherElements(weightContainer,
            [
                workoutContainer,
                caloriesContainer
            ], "hidden")
    });

}

/**
 * Toggles the hidden class on the specified element and adds the hidden
 * class to the other elements given. This is useful for wanting to display
 * one element and hide other elements.
 * @param element the element to toggle the hidden class on.
 * @param elements the elements to add the hidden class to.
 */
function toggleHiddenOnOtherElements(element, elements) {
    toggleClassOnElement(element, "hidden");
    addClassToElements(elements, "hidden");
}

/**
 * Toggles a class on all elements provided.
 * @param elements the elements to toggle the class on.
 * @param classToToggle the class to toggle.
 */
function toggleClassOnElements(elements, classToToggle) {
    for (const element of elements) {
        toggleClassOnElement(element, classToToggle);
    }
}

/**
 * Toggles a css class on a specified element.
 * @param element the element to toggle the class on
 * @param classToToggle the class to toggle on the element
 */
function toggleClassOnElement(element, classToToggle) {
    if (!element.classList.contains(classToToggle)) {
        element.classList.add(classToToggle);
    } else {
        element.classList.remove(classToToggle);
    }
}

/**
 * Adds a class from to elements provided.
 * @param elements the elements to add the class to.
 * @param classToAdd the class to add from the element.
 */
function addClassToElements(elements, classToAdd) {
    for (const element of elements) {
        addClassToElement(element, classToAdd);
    }
}

/**
 * Adds a class to an element.
 * @param element element to add the class to.
 * @param classToAdd the class to add to the element.
 */
function addClassToElement(element, classToAdd) {
    if (!element.classList.contains(classToAdd)) {
        element.classList.add(classToAdd);
    }
}

/**
 * Removes a class from all elements provided.
 * @param elements the elements to remove the class from.
 * @param classToRemove the class to remove from the element.
 */
function removeClassFromElements(elements, classToRemove) {
    for (const element of elements) {
        removeClassFromElement(element, classToRemove);
    }
}

/**
 * Removes a class from an element.
 * @param element element to remove the class from.
 * @param classToRemove the class to remove from the element.
 */
function removeClassFromElement(element, classToRemove) {
    if (element.classList.contains(classToRemove)) {
        element.classList.remove(classToRemove);
    }
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

    // Set the date in a "yyyy-mm-dd" format
    let dateValue = year.toString() + "-" + month.toString() + "-" + day.toString();

    // Update all date inputs to have the current day as it's value and max
    // value
    for (const dateInput of dateInputs) {
        dateInput.setAttribute("value", dateValue);
        dateInput.setAttribute("max", dateValue);
    }
}