<?php

/**
 * This is simple PHP script that handles The file names to be downloaded, creates a unique
 * ZIP file containing, those files, and allows the user to download it as a zip file.
 */

include __DIR__ . './../helpers.php';
// die(basePath('tmp'));

header('Content-Type: application/json'); // Set response type to JSON
header('Access-Control-Allow-Origin: *'); // Enable CORS if needed
header('Access-Control-Allow-Methods: POST'); // Allow POST requests

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the raw POST data
  $fileNames = jsonDataResolver(); // return $data
  // var_dump($fileNames);
  // die();


  // Validate input
  if (!is_array($fileNames)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input: expected array of filenames']);
    exit;
    // return redirect('');
  }

  // Create a unique filename for the ZIP

  /**
   * Different methods to create a unique '.zip' filename
   */

  // $zipFilename = 'download_' . uniqid() . '.zip';

  // $zipFilename = 'Download_' . bin2hex(3) . '.zip';

  // Or usign timestamp
  $zipFilename = 'Download_' . date('F_i_s') . '.zip';

  $downloadDir = basePath('tmp');

  $zipPath = basePath('tmp/' . $zipFilename);
  // die($zipPath); //TEST
  // Ensure the tmp directory exists
  if (!file_exists($downloadDir)) {
    mkdir(basePath('tmp'), 0755, true);
  }

  /**
   * | ====================== 
   * | Create new ZipArchive
   * | ====================== 
   */
  $zip = new ZipArchive();
  if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    $addedFiles = 0;

    // Try to add each file from the list
    foreach ($fileNames as $filename) {
      // echo $filename;
      // die();
      // Remove the dot notation at the beginning in each filename
      // $filename = str_replace('/', '', $filename);
      // die($filename);

      $filePath = $filename; // Adjust path as needed
      // die($filePath);

      if (file_exists(basePath($filePath)) && basePath(is_readable($filePath))) {
        $zip->addFile(basePath($filePath), basename($filename));
        $addedFiles++;
      }
    }

    $zip->close();

    if ($addedFiles > 0) {
      // Return download URL or success message
      echo json_encode([
        'success' => true,
        'downloadUrl' => $downloadDir . $zipFilename,
        'filename' => $zipFilename
      ]);
      exit;
    } else {
      http_response_code(400);
      echo json_encode(['error' => 'None of the requested files were found']);
      exit;
    }
  } else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create ZIP file']);
    exit;
  }
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
