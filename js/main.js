/**
 * Global variables
 */
const submitBtn = document.getElementById("submit");
const fileIn = document.getElementById("fileShared");

/**
 * Helper functions
 */
// Disable the auit button by defualt
submitBtn.setAttribute("disabled", true);

if (fileIn.files.length > 0) {
    submitBtn.removeAttribute("disabled");
} else {
    submitBtn.setAttribute("disabled", true);
}

// Add event listener to detect file selection
const fileHandler = e => {
    if (e.target.files.length > 0) {
        submitBtn.removeAttribute("disabled");
    } else {
        submitBtn.setAttribute("disabled", true);
    }
};

fileIn.addEventListener("change", fileHandler);
