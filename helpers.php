<?php

/**
 * Get the base path
 */

function basePath($path = '')
{
  return __DIR__ . '/' . $path;
}

/**
 * Load a Partial
 */

function loadPartial($name, $data = [])
{
  $partialPath =  basePath("partials/{$name}.php");
  if (file_exists($partialPath)) {
    extract($data);
    require $partialPath;
  } else {
    echo "Partial {$name} not Found!";
  }
}
/**
 * Redirect to a given url
 *
 * @param string $url
 * @return void
 */
function redirect($url)
{
  header("Location: {$url}");
  exit;
}

// Sort files by last modified time (descending)
// function files_sort($array, $dir)
// {
//   usort($array, function ($a, $b) use ($dir) {
//     $fileA = $dir . '/' . $a;
//     $fileB = $dir . '/' . $b;
//     var_dump(filemtime($fileB) - filemtime($fileA));
//     echo '<br />';
//   });
// }

function formatFileSize($bytes)
{
  if ($bytes < 1024) {
    return $bytes . ' Bytes';
  } elseif ($bytes < 1048576) { // 1024 * 1024
    return round($bytes / 1024, 2) . ' KB';
  } elseif ($bytes < 1073741824) { // 1024 * 1024 * 1024
    return round($bytes / 1048576, 2) . ' MB';
  } else {
    return round($bytes / 1073741824, 2) . ' GB';
  }
}
/**
 * Get the file size 
 */
function get_file_size($file, $dir = './')
{
  $file_size_in_bytes =  filesize($dir . '/' . $file);
  return formatFileSize($file_size_in_bytes) . "<br>";
};


// NEW: 
/**
 * For the API
 * @param array $data
 * @return array "json"
 */
function jsonData($data = [])
{
  // Set response header
  header('Content-Type: application/json; charset=utf-8');

  // Return JSON-encoded data
  return json_encode($data);
}

function jsonDataResolver()
{
  // Get the raw POST data
  $input = file_get_contents('php://input');
  // Decode JSON to an associative array
  $data = json_decode($input, true);

  return $data;
}
