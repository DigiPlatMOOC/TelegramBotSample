<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Basic message sending script for your bot.
 * Start editing here. =)
 */

include ('lib.php');

// Put the chat identifier here in order to send messages from your bot
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
