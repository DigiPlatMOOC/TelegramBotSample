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

// Get input contents
// Notice: we use php://stdin (the HTTP request body) normally, but switch
//         over to php://stdin (standard input channel) when running from
//         command line, in order to let you test the script via input pipe
$content = file_get_contents((php_sapi_name() == "cli") ? "php://stdin" : "php://input");

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
