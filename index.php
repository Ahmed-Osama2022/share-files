<?php
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
//$file_status = false;
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
        //  echo "<h3 class='text-success'>File '$fileName' uploaded successfully.</h3>" .
        //  "<br>";
        $file_status = true;

        // "<hr>";
      } else {
        //  echo "<h3 class='text-danger'>Error moving file '$fileName' to upload directory.</h3>" .
        // "<br>";
        $file_status = false;
      }
    } else {
      // echo "<h3 class='text-danger'>Error uploading file '$fileName' with error code: $fileError.</h3>" .
      // "<br>";
      $file_status = false;
    }
  }
}
?>

<!doctype html>
<html>

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="stylesheet"
    href="./node_modules/bootstrap/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="./css/style.css" />
  <script
    type="text/javascript"
    src="./node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

  <title>Share Files</title>
</head>

<body>
  <nav class="navbar bg-body-tertiary shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="index.php">
        <img src="./assets/logo.png" alt="Logo" width="34" height="34" />
        <span class="ps-2">Share Files</span>
      </a>
    </div>
  </nav>

  <form
    method="POST"
    class="text-center mt-3 p-3"
    enctype="multipart/form-data">
    <div class="p-3 bg-light rounded shadow">
      <h2>Please upload your files!</h2>
      <!-- <h2>Android && IOS Transfer files</h2> -->
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
    </div>
  </form>

  <script src="./js/main.js" type="text/javascript" charset="utf-8"></script>
</body>

</html>