<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Conversational message processing functionality,
 * that can be used by both pull and push scripts.
 * Modify pull.php (or hook.php) and switch the
 * default code (msg_processing_simple.php) with this
 * one.
 */

/**
 * Processes conversations between the bot and users.
 * Makes use of the database to keep track of conversation
 * state (make sure the setup.php script has been run for
 * this to work).
 *
 * @return bool True if the message was handled.
 */
function handle_conversation($chat_id, $from_id, $message) {
    $conv = db_row_query("SELECT `user_id`, `state` FROM `conversation` WHERE `user_id` = $from_id");
    if($conv === false) {
        // The query failed, perhaps the database was not configured correctly?
        // See https://github.com/DigiPlatMOOC/TelegramBotSample for the how-to
        return false;
    }
    if($conv == null) {
        // No existing conversation with the user
        // Let the code below handle this message
        return false;
    }

    $state = $conv[1];
    $text = $message['text'];

    switch($state) {
        case 1:
            // Here we are handling the response to "what is your favorite color?"
            telegram_send_message($chat_id, "Ok, you told me your favorite color is $text!", null);

            // This would be a nice place to store the user's favorite color

            // This conversation is over, remove its state from the database
            db_perform_action("DELETE FROM `conversation` WHERE `user_id` = $from_id");
            return true;
    }

    return false;
}

// This file assumes to be included by pull.php or
// hook.php right after receiving a new message.
// It also assumes that the message data is stored
// inside a $message variable.

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
$from_id = $message['from']['id'];

if (isset($message['text'])) {
    // We got an incoming text message
    $text = $message['text'];

    if (strpos($text, "/start") === 0) {
        echo 'Received /start command!' . PHP_EOL;

        telegram_send_message($chat_id, "Hello " . $message['from']['first_name'] . "! Try starting a conversation with the /conversation command.");
    }
    else if (strpos($text, "/conversation") === 0) {
        echo "Starting a conversation for user $from_id" . PHP_EOL;

        // Store '1' as the current conversation state for the current user
        // The next message sent by the same user will be handled by the code
        // in the function handle_conversation()
        db_perform_action("REPLACE INTO `conversation` VALUES($from_id, 1)");

        telegram_send_message($chat_id, "What is your favorite color?");
    }
    else if(handle_conversation($chat_id, $from_id, $message)) {
        // Message is not a command and was handled by the
        // conversation code (above), we're finished here
        return;
    }
    else {
        // Something else
        // ...do something else

        echo "Received unhandled message: $text" . PHP_EOL;

        telegram_send_message($chat_id, "I didn't understand, sorry");
    }
}
else {
    if(telegram_send_message($chat_id, 'Sorry, I understand only text messages!') === false) {
        Logger::error('Failed to answer in chat', __FILE__);
    }
}
?>
