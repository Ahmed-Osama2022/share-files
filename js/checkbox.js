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
const filesAvaliable = document.querySelectorAll('.file-name');

console.log(filesAvaliable); // Test


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

// Default value for the download is set to 'disabled';
btnState(true);

/**
 * The main function for handling the check boxes
 */
// Set a counter for the selectAll Btn
let count = 0;

allCheckboxes.forEach((el) => {
  el.addEventListener('change' ,(box) => {
    if (box.target.checked) {
      // console.log('Checked'); // Test
      selectedLinks.push(box.target.id);
      count++;
      console.log(selectedLinks); // Test
    } else {
      console.log('unchecked'); // Test
      selectedLinks = selectedLinks.filter((id) => id !== box.target.id);
      count--;
      console.log(selectedLinks); // Test
      
    }
    // console.log(count);
    
    // NEW: 
    // Making sure when all of the buttons are checked => making the selectAll btn checked also
    if ( count === filesAvaliable.length ) {
      selectAllBtn.checked = true;
    } else if (count < filesAvaliable.length) {
      selectAllBtn.checked = false;
    }

    if (selectedLinks.length !== 0 && selectedLinks.length <= filesAvaliable.length  ) {
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

  // let index = 0;

  function downloadNext() {
    if (index < selectedLinks.length) {
      const el = selectedLinks[index];

      setTimeout(() => {
        const a = document.createElement('a');
        a.href = el;
        a.classList.add('d-none');
        a.download = el.split('/').pop(); // Extract file name
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
      
      // Trigger the function if only the button is unckecked!
      if (btn.checked === false) {
        btn.checked = true;
        btn.dispatchEvent(new Event('change', { bubbles: true })); // Trigger change event
      }
    });
    
  } else {
    console.log('All unchecked');
    
    allCheckboxes.forEach(btn => {
      
      // Trigger the function if only the button is unckecked!
      if (btn.checked === true) {
        btn.checked = false;
        btn.dispatchEvent(new Event('change', { bubbles: true })); // Trigger change event
      }

    });
  }
})



// let num = 0;
// console.log(selectAllBtn.checked);

// allCheckboxes.forEach(box => {
//   if (box.checked === true){
    // num++;
    // console.log(num);
//   }
  
  // if (num === allCheckboxes.length) {
//     selectAllBtn.checked  = true;
//   }
// })
