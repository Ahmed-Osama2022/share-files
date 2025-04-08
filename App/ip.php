<?php
// Detect the operating system
$os = strtolower(PHP_OS);

// Decalre an embty ip value, to use later in 'qr_code.php' file.
$ip = '';

// Show the flash message
$ip_message = null;


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
  $ip_message = 'Sorry, Unsupported operating system to get the ip.';
}

// Exclude the loopback address (127.0.0.1) and print valid IP addresses
$ipAddresses = array_filter($matches[1], function ($ip) {
  return $ip !== '127.0.0.1';
});

if (!empty($ipAddresses)) {
  foreach ($ipAddresses as $ip) {
    // echo "IP Address: $ip\n";
  }
} else {
  // $ip_message = 'No network avaliable to share!<br>Please make sure you are connected to a network first!<br>to show the Qr-code && be able to share to other devices';
  $ip_message = 'No network avaliable to share!<br>To generate Qr-code, Make sure you are connected to network first!';
}
