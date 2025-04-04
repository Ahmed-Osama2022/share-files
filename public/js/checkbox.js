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
      // console.log(selectedLinks); // Test
    } else {
      console.log('unchecked'); // Test
      selectedLinks = selectedLinks.filter((id) => id !== box.target.id);
      count--;
      // console.log(selectedLinks); // Test
      
    }
    
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
        if (selectedLinks.length <= 1) {
          downloadBtn.innerHTML = 
        `\
        Download ${selectedLinks.length} files \
        `;
        } else {
          downloadBtn.innerHTML = 
          `\
          Download ${selectedLinks.length} files as .zip file \
          `;
        }
    } else {
      btnState(true);
      downloadBtn.innerText = 'Download selected'
    }
})

});
  

/**
 * The download Btn handler function 
 */
// let index = 0;

// downloadBtn.addEventListener('click', () => {
//   console.log(selectedLinks); // Testing!

//   let index = 0;

//   function downloadNext() {
//     if (index < selectedLinks.length) {
//       const el = selectedLinks[index];

//       setTimeout(() => {
//         const a = document.createElement('a');
//         a.href = el;
//         a.classList.add('d-none');
//         a.download = el.split('/').pop(); // Extract file name
//         document.body.appendChild(a);
//         a.click();
//         document.body.removeChild(a);

//         index++;
//         downloadNext(); // Call the function recursively to download the next file
//       }, 500); // 500 milli-second delay to avoid browser blocking
//     }
//   }

//   downloadNext();
// });


/**
 * | ========== The download Btn handler function => For zip.php | ===========
 */
/**
 * Send an array of data to the server to zip it into one file; then return the response,
  * The response will be the zip file;
  * But, if the files is > 1 file => 2 files and above.
  */

const URL = '/App/zip.php';

const uploadData = async (data = []) => {
  const response = await fetch(URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  });


  const result = await response.json();
  if (result.success) {
    // For success
    console.log(result.downloadUrl);

      const a = document.createElement('a');
      a.href = result.downloadUrl;
      // a.innerText = result.filename;
      a.classList.add('d-none');
      document.body.appendChild(a);

      a.download = result.filename;

      console.log(a);
      
    // Some browsers need a small delay before click
    setTimeout(() => {
      a.click();
      
      // Clean up after click
      setTimeout(() => {
        document.body.removeChild(a);
      }, 100);
    }, 10);


    
  } else {
    console.error(result.error);
  }
}

/** 
 * Running the download function
 */
downloadBtn.addEventListener('click', () => {
  uploadData(selectedLinks);
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
