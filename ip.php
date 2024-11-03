<?php
// Detect the operating system
$os = strtolower(PHP_OS);

if (strpos($os, 'win') !== false) {
  // Windows system
  $networkData = shell_exec("ipconfig");
  // Use a regular expression to extract IPv4 addresses
  preg_match_all('/IPv4 Address[. ]*: ([\d.]+)/', $networkData, $matches);
} elseif (strpos($os, 'darwin') !== false || strpos($os, 'linux') !== false) {
  // macOS or Linux system
  $networkData = shell_exec("ifconfig 2>/dev/null || ip a");
  // Use a regular expression to extract IPv4 addresses
  preg_match_all('/inet (\d+\.\d+\.\d+\.\d+)/', $networkData, $matches);
} else {
  // die("Unsupported operating system.");
  echo "Sorry, Unsupported operating system to get the ip.";
}

// Exclude the loopback address (127.0.0.1) and print valid IP addresses
$ipAddresses = array_filter($matches[1], function ($ip) {
  return $ip !== '127.0.0.1';
});

if (!empty($ipAddresses)) {
  foreach ($ipAddresses as $ip) {
    echo "IP Address: $ip\n";
  }
} else {
  // echo "No valid IP addresses found.\n";
  // echo "Please make sure you are connected to a network first!\n";
  echo "No network avaliable to share!<br>Please make sure you are connected to a network first!";
}
// echo '<br />';
// echo $_SERVER['SERVER_PORT'];
