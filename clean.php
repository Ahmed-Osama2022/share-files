<?php

/**
 * This page is responsable for the cleaning the files inside 
 * "/uploads" && "/tmp"
 */
include './helpers.php';

// Define the dirs 
$dir1 = basePath('uploads');
$dir2 = basePath('tmp');

$btn_status  = ''; // '' || 'disabled'

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


// Ensure the tmp directory exists
if (!file_exists($dir1)) {
  // echo "The Upload folder doesn't exists";
  // $message_dir1 = "The Upload folder doesn't exists";
  $messages['FoundUpload'] = "The Upload folder doesn't exists";
  $messages['Dir_style1'] = 'text-danger';
} else {
  $messages['FoundUpload'] = "Found the Upload Dir!";
  $messages['Dir_style1'] = 'text-success';

  // Get all files and directories inside the target directory
  $files1 = array_diff(scandir($dir1), array('.', '..'));

  // Check if the Dir has files inside it
  if (count($files1) >= 1) {
    $btn_status = '';
    $messages['File_style1'] = 'text-success';
    $messages['FoundFiles_dir1'] = "Folder Uploads contains a " .  count($files1) . ' Files';
  } else {
    $btn_status = 'disabled';
    $messages['FoundFiles_dir1'] = "Folder Uploads Is empty, and no need to clean.";
    $messages['File_style1'] = 'text-danger';
  }
}

// var_dump($files1); // TEST

/**
 * Function to delete all of the files
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  // echo "Ahmed";

  // Loop through each file/directory
  foreach ($files1 as $file) {
    $filePath = $dir1 . DIRECTORY_SEPARATOR . $file;

    // echo $filePath . '<br>';
    // If it's a file, delete it
    unlink($filePath);
  }
}




// Check the second dir => "/tmp"
// if (!file_exists($dir2)) {
//   // echo "The tmp folder doesn't exists";
//   $message_dir2 = "The tmp folder doesn't exists";
// } else {
//   $files2 = array_diff(scandir($dir2), array('.', '..'));
// }
// // $filePath = $dir2 . DIRECTORY_SEPARATOR . $file;


?>

<?= loadPartial('head', ['title' => 'Clean Script']) ?>

<?= loadPartial('navbar') ?>

<body>
  <div class="container text-center bg-white py-5 px-3 my-3 shadow-sm rounded-3">
    <h3>Clean page for cleaning the files uploaded to the server</h3>
    <br>
    <h4>Notes:</h4>

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
      <?= $messages['FoundTmp'] ?>
    </p>

    <p class="text-center p-3  <?= $messages['File_style2'] ?>">
      <?= $messages['FoundFiles_dir2'] ?>
    </p>

    <form method="post">
      <button class="btn btn-danger " <?= $btn_status ?> name="submit" type="submit">Delete All the files</button>
    </form>


  </div>
</body>