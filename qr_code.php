<?php

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

// Make the URL for the users "To join with Qr-code"
$url = 'http://' . $ip . ':' .  $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
// $url = 'https://www.example.com'; // Change this to your desired URL
$builder = new Builder(
  writer: new PngWriter(),
  writerOptions: [],
  validateResult: false,
  data: $url,
  encoding: new Encoding('UTF-8'),
  errorCorrectionLevel: ErrorCorrectionLevel::High,
  size: 200,
  // margin: 10,
  margin: 0,
  roundBlockSizeMode: RoundBlockSizeMode::Margin,
  // logoPath: __DIR__ . '/assets/logo.png',
  logoResizeToWidth: 50,
  logoPunchoutBackground: true,
  labelText: 'This is the label',
  labelFont: new OpenSans(20),
  labelAlignment: LabelAlignment::Center
);

$result = $builder->build();

// Save it to a file

// Generate a data URI to include image data inline (i.e. inside an <img> tag)
$dataUri = $result->getDataUri();

echo "<img src='$dataUri' alt='Logo-qr-code' />";

// Directly output the QR code
// header('Content-Type: ' . $result->getMimeType());
// echo $result->getString();

// Save it to a file
// $result->saveToFile(__DIR__ . '/qrcode.png');

// Generate a data URI to include image data inline (i.e. inside an <img> tag)
// $dataUri = $result->getDataUri();
