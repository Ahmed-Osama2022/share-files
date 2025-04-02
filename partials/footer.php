<script src="js/main.js" type="text/javascript" charset="utf-8"></script>

<?php
$script = '';
function isMobile()
{
  $userAgent = $_SERVER['HTTP_USER_AGENT'];

  // Regular expressions for detecting mobile devices
  $mobileDevices = array(
    'android',
    'iphone',
    'ipod',
    'blackberry',
    'windows phone',
    'mobile',
    'nokia',
    'tablet'
  );

  // Loop through each mobile device keyword
  foreach ($mobileDevices as $device) {
    if (stripos($userAgent, $device) !== false) {

      return true; // Mobile device detected
    }
  }

  return false; // Desktop/laptop detected
}
if (isMobile()) {
  // echo "You are using a mobile device.";
  $script = 'checkboxMobile.js';
} else {
  // echo "You are using a laptop or desktop.";
  $script = 'checkbox.js';
}
?>


<!-- <script src="js/checkbox.js" type="text/javascript" charset="utf-8"></script> -->
<script src="js/<?= $script ?>" type="text/javascript" charset="utf-8"></script>

</body>

</html>