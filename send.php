<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Basic message processing end-point for your bot.
 * Start editing here. =)
 */

include ("lib.php");

define('CHAT_ID', 123456789);

if(count($argv) < 2) {
    echo ('Must pass message to send as command line parameter.' . PHP_EOL);
    exit;
}

$message = $argv[1];
echo "Message is '$message'." . PHP_EOL;

echo ('Executing request...' . PHP_EOL);
$response = telegram_send_message(CHAT_ID, $message, array (
    'disable_web_page_preview' => true
));

if($response === false) {
    echo ("Request failed." . PHP_EOL);
}
else {
    echo ("Response:" . PHP_EOL);
    print_r($response);
}
?>
