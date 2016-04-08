<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Basic message processing in pull mode for your bot.
 * Start editing here. =)
 */

include ('lib_msg_processing.php');

// TODO: implement persistent update store (file_get_contents).

// Fetch updates from API
// Note: we do not remember the last fetched ID and simply query the
//       first new message returned.
$content = telegram_get_updates(null, 1, false);
if($content === false) {
    error_log('Failed to fetch updates from API');
    exit;
}
if(count($content) == 0) {
    echo ('No new messages.' . PHP_EOL);
    exit;
}

$first_update = $content[0];

echo ('New update received:' . PHP_EOL);
print_r($first_update);

// Updates have the following structure:
// [
// {
//     "update_id": 123456789,
//     "message": {
//          ** message object **
//     }
// }
// ]

$update_id = $first_update['update_id'];
$message = $first_update['message'];

// TODO: update persistent store (file_put_contents).

process_message($message);
?>
