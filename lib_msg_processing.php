<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Basic message processing functionality,
 * used by both pull and push scripts.
 *
 * Put your custom bot intelligence here!
 */

include ('lib.php');

/**
 * Processes a message received through the Telegram API.
 * @param object $message A message object as parsed from the Telegram API.
 * @return Nothing.
 */
function process_message($message) {
    // Extract important information from the message object
    //
    // Message object structure: {
    //     "message_id": 123,
    //     "from": {
    //       "id": 123456789,
    //       "first_name": "First",
    //       "last_name": "Last",
    //       "username": "FirstLast"
    //     },
    //     "chat": {
    //       "id": 123456789,
    //       "first_name": "First",
    //       "last_name": "Last",
    //       "username": "FirstLast",
    //       "type": "private"
    //     },
    //     "date": 1460036220,
    //     "text": "Text"
    //   }
    $message_id = $message['message_id'];
    $chat_id = $message['chat']['id'];

    if (isset($message['text'])) {
        // We got an incoming text message
        $text = $message['text'];

        if (strpos($text, "/start") === 0) {
            // Start command
            // ...do something

            echo 'Received /start command!' . PHP_EOL;
        }
        else {
            // Something else
            // ...do something else

            echo "Received message: $text!" . PHP_EOL;
    	}
    }
    else {
        if(telegram_send_message($chat_id, 'Sorry, I understand only text messages!', null) === false) {
            error_log('Failed to answer in chat');
        }
    }
}
?>
