<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * API sample, using Random.org.
 */

include ('../lib.php');

define('RANDOM_ORG_API_KEY', '');

$method_body = array(
    'jsonrpc' => '2.0',
    'method' => 'generateIntegers',
    'params' => array(
        'apiKey' => RANDOM_ORG_API_KEY,
        'n' => 1,
        'min' => 1,
        'max' => 6
    ),
    'id' => 42
);

$handle = prepare_curl_api_request('https://api.random.org/json-rpc/1/invoke', 'POST',
    null,
    json_encode($method_body),
    array(
        'Content-Type: application/json-rpc'
    )
);

$response = perform_curl_request($handle);
if($response === false) {
    Logger::fatal('Failed to perform request', __FILE__);
}

Logger::debug('Response from Random.org API: '. print_r($response, true));

Logger::info('Random number: ' . json_decode($response, true)['result']['random']['data'][0], __FILE__);
?>
