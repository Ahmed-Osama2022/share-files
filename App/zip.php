<?php

/**
 * This is simple PHP script that handles The file names to be downloaded, creates a unique
 * ZIP file containing, those files, and allows the user to download it as a zip file.
 */

include __DIR__ . './../helpers.php';

header('Content-Type: application/json'); // Set response type to JSON
header('Access-Control-Allow-Origin: *'); // Enable CORS if needed
header('Access-Control-Allow-Methods: POST'); // Allow POST requests

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the raw POST data
  $fileNames = jsonDataResolver(); // return $data

  // Validate input
  if (!is_array($fileNames)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input: expected array of filenames']);
    exit;
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

  // Ensure the tmp directory exists
  if (!file_exists($downloadDir)) {
    mkdir(basePath('tmp'), 0755, true);
  }

  /**
   * | ====================== 
   * | Create new ZipArchive
   * | ====================== 
   */
  /**
   * The response will be the zip file;
   * But, if the files is > 1 file => 2 files and above.
   */
  if (count($fileNames) === 1) {
    echo json_encode([
      'success' => true,
      'downloadUrl' =>  $fileNames[0], // TRUE
      'filename' => $fileNames
    ]);
    // return;
    exit;
  }

  $zip = new ZipArchive();
  if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    $addedFiles = 0;

    // Try to add each file from the list
    foreach ($fileNames as $filename) {

      $filePath = $filename; // Adjust path as needed

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
        // 'downloadUrl' => $downloadDir . $zipFilename, // BUG:
        'downloadUrl' => 'tmp' . '/' .   $zipFilename, // TRUE
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
// To make sure the user can't request this file by GET request
echo json_encode(['error' => 'Method not allowed']);
