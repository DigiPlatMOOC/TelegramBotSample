<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * API sample, using Microsoft Bing reverse geolocation API.
 */

define('BING_API', '');

include ('../lib.php');

if(count($argv) < 3) {
    echo ('Must pass geographic coordinates (lat and long) as command line parameter.' . PHP_EOL);
    exit;
}

$lat = floatval($argv[1]);
$lng = floatval($argv[2]);

$handle = prepare_curl_api_request("http://dev.virtualearth.net/REST/v1/Locations/$lat,$lng?key=" . BING_API, 'GET');

$response = perform_curl_request($handle);
if($response === false) {
    Logger::fatal('Failed to perform request.', __FILE__);
}

$json = json_decode($response, true);
if(!$json['resourceSets']) {
    Logger::fatal('Response contains no resource sets', __FILE__);
}

$sets = $json['resourceSets'];
$s = 1;

foreach($sets as $set) {
    echo "Resource set #$s" . PHP_EOL;

    $resources = $sets[0]['resources'];
    $r = 1;

    foreach($resources as $resource) {
        echo "Resource #$r" . PHP_EOL;

        $address = $resource['address'];
        $confidence = $resource['confidence'];

        echo 'Address: ';
        print_r($address);

        echo "Confidence: $confidence" . PHP_EOL;

        $r++;
    }

    $s++;
}
?>
