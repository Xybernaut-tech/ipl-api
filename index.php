<?php
// Check if the request is coming from your domain
$allowedDomain = 'https://binge-player.pages.dev';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

if (
    (strpos($referer, $allowedDomain) !== 0) &&
    (strpos($origin, $allowedDomain) !== 0)
) {
    header('HTTP/1.1 403 Forbidden');
    die("Access denied");
}

// Allow cross-origin access
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/dash+xml");
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Get stream ID
$get = isset($_GET['get']) ? $_GET['get'] : '';
if (!$get) {
    die("No stream identifier provided");
}

$mpdUrl = 'https://linearjitp-playback.astro.com.my/dash-wv/linear/' . $get;

// Set headers for the request
$mpdheads = [
  'http' => [
      'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36\r\n",
      'follow_location' => 1,
      'timeout' => 5
  ]
];

$context = stream_context_create($mpdheads);
$res = file_get_contents($mpdUrl, false, $context);

if ($res === false) {
    die("Failed to fetch MPD file.");
}

echo $res;
?>
