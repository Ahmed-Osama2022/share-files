<?php

/**
 * This is simple PHP script that handles "file uploads", creates a unique ZIP file containing,* those files, and allows the user to download it as a zip file;
 * 
 */

// die(basePath());

/** 
 * Upload the files in the root directory fo the App
 * 
 */





if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
  die('POST Method');
  // Create a unique filename for the ZIP
  $zipFilename = 'download_' . uniqid() . '.zip';
  $zipPath = __DIR__ . '/tmp/' . $zipFilename;

  // Ensure the tmp directory exists
  if (!file_exists(__DIR__ . '/tmp')) {
    mkdir(__DIR__ . '/tmp', 0755, true);
  }

  // Check if any files were uploaded
  if (!empty($_FILES['files']['name'][0])) {
    // Create new ZipArchive
    $zip = new ZipArchive();
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
      // Add each uploaded file to the ZIP
      foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {
          $filename = basename($_FILES['files']['name'][$key]);
          $zip->addFile($tmpName, $filename);
        }
      }

      $zip->close();

      // Send the ZIP to the browser
      header('Content-Type: application/zip');
      header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
      header('Content-Length: ' . filesize($zipPath));
      readfile($zipPath);

      // Clean up (delete the temporary ZIP)
      // unlink($zipPath);
      exit;
    } else {
      echo "Failed to create ZIP file";
    }
  } else {
    echo "No files were uploaded";
  }
}
?>

<!-- <!DOCTYPE html>
<html>

<head>
  <title>File to ZIP Converter</title>
</head>

<body>
  <h2>Upload Files to ZIP</h2>
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="files[]" multiple>
    <button type="submit">Create ZIP Download</button>
  </form>
</body>

</html> -->