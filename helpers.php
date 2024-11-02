<?php

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
