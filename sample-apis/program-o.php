<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * API sample, using the web API of Program-O.
 */

include ('../lib.php');

if(count($argv) < 2) {
    echo ('Must pass message to send as command line parameter.' . PHP_EOL);
    exit;
}

$message = $argv[1];

$handle = prepare_curl_api_request('http://api.program-o.com/v2/chatbot/', 'POST',
    array(
        'say' => $message,
        'bot_id' => 6,
        'format' => 'json',
        'convo_id' => 'uwiclab-bot-sample'
    ),
    null,
    array(
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json'
    )
);

$response = perform_curl_request($handle);
if($response === false) {
    Logger::fatal('Failed to perform request', __FILE__);
}

Logger::debug('Response from Program-O bot: '. print_r($response, true));

$json_response = json_decode($response, true);
Logger::info('You said: ' . $json_response['usersay'], __FILE__);
Logger::info('Bot says: ' . $json_response['botsay'], __FILE__);
?>
