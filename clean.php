<?php

/**
 * This page is responsable for the cleaning the files inside 
 * "/uploads" && "/tmp"
 */
include './helpers.php';

/**
 * Prevent other users on ther server from access this page;
 */
$client_ip = $_SERVER['REMOTE_ADDR'];

// Debug: just to check IP during development
// echo "Client IP: $client_ip";
// die();

if ($client_ip !== '127.0.0.1' && $client_ip !== '::1') {
  // Request NOT from localhost
  echo "<h1 style='text-align: center; padding: 30px'>
    Access denied. Only localhost can see this page!
  </h1>";
  exit;
}

// Define the dirs 
$dir1 = basePath('uploads');
$dir2 = basePath('tmp');

// Defines the files array 
$files1 = []; // For /uploads
$files2 = []; // for /tmp

$btn_status  = 'disabled'; // '' || 'disabled'

// Falsh messages
$messages = [
  'FoundUpload' => '',
  'FoundTmp' => '',

  'Dir_style1' => '',
  'File_style1' => '',

  'Dir_style2' => '',
  'File_style2' => '',

  'FoundFiles_dir1' => '',
  'FoundFiles_dir2' => ''
];

/**
 * | ================================== |
 *  Function for the "Upload DIR"
 * | ================================== |
 */
// Ensure the directory exists
if (!file_exists($dir1)) {
  $messages['FoundUpload'] = "The (Upload) folder doesn't exists";
  $messages['Dir_style1'] = 'text-danger';
} else {
  $messages['FoundUpload'] = "Found the (Upload) Dir!";
  $messages['Dir_style1'] = 'text-success';

  // Get all files and directories inside the target directory
  $files1 = array_diff(scandir($dir1), array('.', '..'));

  // Check if the Dir has files inside it
  if (count($files1) >= 1) {
    $btn_status = '';
    $messages['File_style1'] = 'text-success';
    $messages['FoundFiles_dir1'] = "Folder (Uploads) contains a " .  count($files1) . ' Files';
  } else {
    $btn_status = 'disabled';
    $messages['FoundFiles_dir1'] = "Folder (Uploads) Is empty, and no need to clean.";
    $messages['File_style1'] = 'text-danger';
  }
}

/**
 * | ================================== |
 *  Function for the "tmp DIR"
 * | ================================== |
 */
// Ensure the directory exists
if (!file_exists($dir2)) {
  // echo "The Upload folder doesn't exists";
  // $message_dir2 = "The Upload folder doesn't exists";
  $messages['FoundtTmp'] = "The (tmp) folder doesn't exists (For zipped files)";
  $messages['Dir_style2'] = 'text-danger';
} else {
  $messages['FoundtTmp'] = "Found the (tmp) Dir! (For zipped files)";
  $messages['Dir_style2'] = 'text-success';

  // Get all files and directories inside the target directory
  $files2 = array_diff(scandir($dir2), array('.', '..'));

  // Check if the Dir has files inside it
  if (count($files2) >= 1) {
    $btn_status = '';
    $messages['File_style2'] = 'text-success';
    $messages['FoundFiles_dir2'] = "Folder (tmp) contains a " .  count($files2) . ' Files';
  } else {
    // $btn_status = 'disabled'; BUG:
    $messages['FoundFiles_dir2'] = "Folder (tmp) Is empty, and no need to clean.";
    $messages['File_style2'] = 'text-danger';
  }
}

/**
 * Function to delete all of the files
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  // Loop through each file/directory

  if (count($files1) !== 0) {
    foreach ($files1 as $file) {
      $filePath = $dir1 . DIRECTORY_SEPARATOR . $file;
      // If it's a file, delete it
      unlink($filePath);
      // sleep(3);
    }
  }

  if (count($files2) !== 0) {
    foreach ($files2 as $file) {
      $filePath = $dir2 . DIRECTORY_SEPARATOR . $file;
      unlink($filePath);
    }
  }
  return redirect('clean.php');
}

?>

<!-- | =========== Front-End ============== | -->
<?= loadPartial('head', ['title' => 'Clean Page']) ?>

<?= loadPartial('navbar') ?>

<body>
  <div class="container text-center bg-white py-5 px-3 my-3 shadow-sm rounded-3">
    <h3>Clean page for cleaning the files uploaded to the server</h3>
    <br>
    <h4 class="text-decoration-underline">Notes:</h4>

    <!-- | ============ 1- For the "Upload" Directory && Files ============ | -->
    <p class="text-center mt-3 mt-3 <?= $messages['Dir_style1'] ?>">
      <?= $messages['FoundUpload'] ?>
    </p>

    <p class="text-center p-3 <?= $messages['File_style1'] ?>">
      <?= $messages['FoundFiles_dir1'] ?>
    </p>

    <!-- |======================================== | -->
    <!-- | ============ 2- For the "tmp" Directory && Files ============ | -->
    <p class="text-center p-3  <?= $messages['Dir_style2'] ?> ">
      <?= $messages['FoundtTmp'] ?>
    </p>

    <p class="text-center p-3  <?= $messages['File_style2'] ?>">
      <?= $messages['FoundFiles_dir2'] ?>
    </p>

    <form method="post" id="delete-form">
      <button class="btn btn-danger " <?= $btn_status ?> name="submit" type="submit">Delete All the files</button>

    </form>
  </div>

  <!-- For confirmation -->
  <script>
    const form = document.getElementById('delete-form');

    form.addEventListener('submit', function(e) {
      const result = confirm('Are you sure you want to delete all the files?');

      if (!result) {
        e.preventDefault(); // This now works perfectly
      }
    });
  </script>

</body>


</html>