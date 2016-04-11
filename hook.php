<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Basic message processing webhook end-point for your bot.
 * Start editing here. =)
 */

include ('lib_msg_processing.php');

// Get contents from HTTP request
$content = stream_get_contents(STDIN);

// Decode contents as JSON
$update = json_decode($content, true);

if (!$update) {
    error_log('Bad message received (not JSON)');
    exit;
}
else {
    if (isset($update['message'])) {
        process_message($update['message']);
    }
    else {
        error_log('Bad message received (no message field)');
        exit;
    }
}
?>
