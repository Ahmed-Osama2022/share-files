/**
 * This file is responsable for the functionality of the Download button and checkboxes
 */

/**
 * Global variables
 */

// NOTE: Exclude the "SelectAll" button from the list;
function getAllBoxes() {
  return [...document.querySelectorAll('.form-check-input')].filter(input => input.id !== 'selectAll');
}

// | ====== OLD =======
// let allCheckboxes = document.querySelectorAll('.form-check-input');
// | ====== NEW =======
let allCheckboxes =getAllBoxes();
const selectAllBtn = document.getElementById('selectAll');
const downloadBtn = document.getElementById('downloadBtn');

let selectedLinks = [];

/**
 * States for the button
 */
// Function to Prevent the user from click on the button when nothing is selected yet
function btnState(value) {
  if (value === true){
    downloadBtn.setAttribute('disabled','');
  }
  else if (value === false){
    downloadBtn.removeAttribute('disabled');
  }
}

// State for SelectAll Btn;
function allBtnState(value) {
  selectAllBtn.checked = value;
}


// Default value for the download is set to 'disabled';
btnState(true);

/**
 * The main function for handling the check boxes
 */
allCheckboxes.forEach((el) => {
  el.addEventListener('change' ,(box) => {
    if (box.target.checked) {
      console.log('Checked');
      selectedLinks.push(box.target.id);
    } else {
      console.log('unchecked');
      selectedLinks = selectedLinks.filter((id) => id !== box.target.id);
    }

    if (selectedLinks.length !== 0) {
      btnState(false);
      // Give the user the count the selected files
      downloadBtn.innerHTML = 
      `\
      Download ${selectedLinks.length} files \
      `;

    } else {
      btnState(true);
      downloadBtn.innerText = 'Download selected'
    }
})

});
  

/**
 * The download Btn handler function 
 */
let index = 0;


downloadBtn.addEventListener('click', () => {
  console.log(selectedLinks); // Testing!

  let index = 0;

  function downloadNext() {
    if (index < selectedLinks.length) {
      const el = selectedLinks[index];

      setTimeout(() => {
        const a = document.createElement('a');
        a.href = el;
        a.classList.add('d-none');
        a.download = el;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        index++;
        downloadNext(); // Call the function recursively to download the next file
      }, 500); // 500 milli-second delay to avoid browser blocking
    }
  }

  downloadNext();
});


/**
 * Function for SelectAll button
 */
selectAllBtn.addEventListener('change' ,(el) => {
  if (el.target.checked === true) {
    console.log('All Checked');

    allCheckboxes.forEach((btn) => {
      // debugger;
      btn.checked = true;
      btn.dispatchEvent(new Event('change', { bubbles: true })); // Trigger change event
    });
  
    
  } else{
    console.log('All unchecked');

    allCheckboxes.forEach(btn => {
      btn.checked = false;
      btn.dispatchEvent(new Event('change', { bubbles: true })); // Trigger change event
    });
  }
})

// Making sure when all of the buttons are checked => making the selectAll btn checked also

// let index = 0;

// allCheckboxes.forEach(box => {
//   if (box.checked === true){
//     index++;
//     console.log(index);
//   }

//   if (index === allCheckboxes.length) {
//     selectAllBtn.checked  = true;
//   }

// })
