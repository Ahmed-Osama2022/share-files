<?php

/**
 * Disable Caching with HTTP Headers
 */
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

require 'vendor/autoload.php';
include './helpers.php';

include basePath('App/ip.php');
include basePath('App/qr_code.php');

// Set upload limits in the script (optional; requires php.ini changes for large uploads)
// ini_set('post_max_size', '100000000000M');
// ini_set('upload_max_filesize', '100000000M');

$messages = [
  "loading" => "Please wait while transferring the file",
  "success" => "File uploaded successfully.",
  "error" => "Error uploading file.",
];
// $file_status = false;
$directory = "uploads/";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
  // Define the target directory for uploads
  $directory = "uploads/";
  if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
  }

  // Loop through each file in the 'userfile' array
  foreach ($_FILES["userfile"]["name"] as $index => $fileName) {
    $fileTmpName = $_FILES["userfile"]["tmp_name"][$index];
    $fileSize = $_FILES["userfile"]["size"][$index];
    $fileError = $_FILES["userfile"]["error"][$index];
    $fileType = $_FILES["userfile"]["type"][$index];

    if ($fileError === 0) {
      $targetFilePath = $directory . basename($fileName);
      // echo "Please wait while transferring the file '$fileName'...<br>";

      if (move_uploaded_file($fileTmpName, $targetFilePath)) {
        $file_status = true;
      } else {
        $file_status = false;
        // return;
      }
    } else {
      $file_status = false;
      // return;
    }
  }
  return redirect('./');
}
?>

<?= loadPartial('head') ?>

<body>
  <?php loadPartial('navbar'); ?>

  <form
    method="POST"
    class="text-center mt-3 p-3 p-md-0"
    enctype="multipart/form-data">
    <div class="p-3 bg-light rounded shadow">
      <h2>Please upload your files!</h2>
      <div class="mb-3 mt-3">
        <label for="fileShared" class="form-label">Choose the files from your device</label>
        <input
          type="file"
          name="userfile[]"
          class="form-control"
          id="fileShared"
          multiple />
      </div>

      <?php if (isset($file_status) && $file_status === true): ?>
        <p class='text-success'><?= $messages["success"] ?> </p>
      <?php elseif (isset($file_status) && $file_status === false): ?>
        <p class='text-danger'><?= $messages["error"] ?> </p>
      <?php endif; ?>

      <button type="submit" name="submit" id="submit" class="btn btn-success">
        Send
      </button>

      <!-- Show the QR-code -->
      <div class="qr-code-wrapper mt-3">
        <img src='<?= $dataUri ?>' alt='Logo-qr-code' width='100' height='100' />
        <p class="mt-1">Scan me to join</p>
      </div>


      <!-- Show the files in the "/uploads" folder in cards-->
      <div class="my-3">

        <?php
        $files_to_share = false;
        $files_arr = [];
        // Check if the directory exists
        if (is_dir($directory)) {
          // Scan the directory for files
          $files_in_dir = scandir($directory);
          // Filter out '.' and '..'
          $files_in_dir = array_filter($files_in_dir, fn($file) => $file !== '.' && $file !== '..');

          // Display the files
          if (!empty($files_in_dir)) {
            $files_to_share = true;
            // echo "Files avaliable to share:<br>";
            foreach ($files_in_dir as $file) {
              array_push($files_arr, $file);
              // files_sort($files_arr, $directory);
            }
          } else {
            echo "<p class='text-muted'>No files avaliable to share</p>";
          }
        } else {
          echo "<p class='text-muted'>No files avaliable to share</p>";
        }
        ?>

        <?php if ($files_to_share): ?>
          <!-- Give the user the count of the files -->
          <p class="fs-5 mt-3">Files avaliable to share: <?= count($files_arr) ?> files</p>

          <!-- SelectAll button -->
          <div class="text-end me-3">
            <span>Select All</span>
            <input type="checkbox" class="form-check-input ms-1" id="selectAll">
          </div>

          <?php foreach ($files_arr as $key => $file): ?>
            <div class="p-3 my-2 bg-white rounded border border-2 file_card d-flex justify-content-between align-items-center ">

              <a href="/uploads/<?= $file ?>" target="__blank" class="fs-6 m-0 text-start file-name"> <?= $key + 1 ?> - <?= $file ?></a>
              <div class="d-flex align-items-center">
                <p class="text-muted pe-2 my-1 file-size"><?= get_file_size($file, $directory); ?></p>
                <a href="/uploads/<?= $file ?>" class="fs-5 " download>
                  <i class="fa-solid fa-circle-down text-success fs-4"></i>
                </a>
                <!-- Checkbox for select item "Single item"-->
                <input type="checkbox" class="form-check-input ms-3" id="/uploads/<?= $file ?>">
              </div>

            </div>
          <?php endforeach; ?>
          <!-- Download Button -->
          <div class="text-end pt-2">
            <button type="button" class="btn btn-outline-success text-end" id="downloadBtn">Download selected</button>
          </div>

        <?php endif; ?>
      </div>

    </div>
  </form>


  <?= loadPartial('footer') ?>