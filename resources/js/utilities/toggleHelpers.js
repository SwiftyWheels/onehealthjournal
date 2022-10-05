// This places the JS in strict mode which will throw errors when the code
// isn't written properly. Helps with debugging.
// This can also be placed inside a function to make the function strict mode
"use strict";

/**
 * Toggles the hidden class on the specified element and adds the hidden
 * class to the other elements given. This is useful for wanting to display
 * one element and hide other elements.
 * @param element the element to toggle the hidden class on.
 * @param elements the elements to add the hidden class to.
 */
export function toggleHiddenOnOtherElements(element, elements) {
    if (element.classList.contains("hidden")) {
        element.classList.remove("hidden");
    }
    addClassToElements(elements, "hidden");
}

/**
 * Toggles a class on all elements provided.
 * @param elements the elements to toggle the class on.
 * @param classToToggle the class to toggle.
 */
export function toggleClassOnElements(elements, classToToggle) {
    for (const element of elements) {
        toggleClassOnElement(element, classToToggle);
    }
}

/**
 * Toggles a css class on a specified element.
 * @param element the element to toggle the class on
 * @param classToToggle the class to toggle on the element
 */
export function toggleClassOnElement(element, classToToggle) {
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
export function addClassToElements(elements, classToAdd) {
    for (const element of elements) {
        addClassToElement(element, classToAdd);
    }
}

/**
 * Adds a class to an element.
 * @param element element to add the class to.
 * @param classToAdd the class to add to the element.
 */
export function addClassToElement(element, classToAdd) {
    if (!element.classList.contains(classToAdd)) {
        element.classList.add(classToAdd);
    }
}

/**
 * Removes a class from all elements provided.
 * @param elements the elements to remove the class from.
 * @param classToRemove the class to remove from the element.
 */
export function removeClassFromElements(elements, classToRemove) {
    for (const element of elements) {
        removeClassFromElement(element, classToRemove);
    }
}

/**
 * Removes a class from an element.
 * @param element element to remove the class from.
 * @param classToRemove the class to remove from the element.
 */
export function removeClassFromElement(element, classToRemove) {
    if (element.classList.contains(classToRemove)) {
        element.classList.remove(classToRemove);
    }
}
