<?php
include './helpers.php';
// header('Location: index.html');
// exit;

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
  // echo "worked";

  // Define the target directory for uploads
  $directory = "uploads/";
  if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
    // echo "Directory 'uploads/' created successfully<br>";
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
        return;
      }
    } else {
      $file_status = false;
      return;
    }
  }
  return redirect('./');
}
?>

<!doctype html>
<html>

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="./css/bootstrap.min.css" />
  <link rel="stylesheet" href="./css/all.min.css" />
  <link rel="stylesheet" href="./css/style.css" />

  <script type="text/javascript" src="./js/bootstrap.min.js"></script>
  <script type="text/javascript" src="./js/all.min.js"></script>
  <title>Share Files</title>
</head>

<body>
  <nav class="navbar bg-body-tertiary shadow-sm rounded">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="./assets/logo.png" alt="Logo" width="34" height="34" />
        <span class="ps-3">Share Files</span>
      </a>
    </div>
  </nav>

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
          <p class="fs-5 mt-5">Files avaliable to share:</p>
          <?php foreach ($files_arr as $file): ?>

            <div class="p-3 my-2 bg-white rounded border border-2 file_card d-flex justify-content-between align-items-center ">
              <a href="./uploads/<?= $file ?>" target="__blank" class="fs-6 m-0 text-start file-name"><?= $file ?></a>
              <div class="d-flex align-items-center">
                <p class="text-muted pe-2 my-1 file-size"><?= get_file_size($file, $directory); ?></p>
                <a href="./uploads/<?= $file ?>" class="fs-5 " download>
                  <i class="fa-solid fa-circle-down text-success fs-4"></i>
                </a>
              </div>

            </div> <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div>
  </form>

  <script src="./js/main.js" type="text/javascript" charset="utf-8"></script>
</body>

</html>