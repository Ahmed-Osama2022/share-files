<?php
// Set upload limits in the script (optional; requires php.ini changes for large uploads)
// ini_set('post_max_size', '100000000000M');
// ini_set('upload_max_filesize', '100000000M');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    echo "worked";

   /*
   echo "<pre>";
  //  var_dump($_FILES['userfile']);
   echo "</pre>";
*/
    // Define the target directory for uploads
    $directory = 'uploads/';
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
        echo "Directory 'uploads/' created successfully<br>";
    }

    // Loop through each file in the 'userfile' array
    foreach ($_FILES['userfile']['name'] as $index => $fileName) {
        $fileTmpName = $_FILES['userfile']['tmp_name'][$index];
        $fileSize = $_FILES['userfile']['size'][$index];
        $fileError = $_FILES['userfile']['error'][$index];
        $fileType = $_FILES['userfile']['type'][$index];

        if ($fileError === 0) {
            $targetFilePath = $directory . basename($fileName);
            echo "Please wait while transferring the file '$fileName'...<br>";

            if (move_uploaded_file($fileTmpName, $targetFilePath)) {
                echo "<h3 class='text-success'>File '$fileName' uploaded successfully.</h3>";
            } else {
                echo "<h3 class='text-danger'>Error moving file '$fileName' to upload directory.</h3>";
            }
        } else {
            echo "<h3 class='text-danger'>Error uploading file '$fileName' with error code: $fileError.</h3>";
        }
    }
}
