<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Configuration file.
 */

define('PROGRAM_O_API_URI','http://api.program-o.com/v2/chatbot');

define('TELEGRAM_BOT_TOKEN', '');
define('TELEGRAM_API_URI_BASE', 'https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/');
define('TELEGRAM_API_URI_MESSAGE', TELEGRAM_API_URI_BASE . 'sendMessage');
define('TELEGRAM_API_URI_LOCATION', TELEGRAM_API_URI_BASE . 'sendLocation');
define('TELEGRAM_API_URI_UPDATES', TELEGRAM_API_URI_BASE . 'getUpdates');

define('DATABASE_HOST', 'localhost');
define('DATABASE_NAME', '');
define('DATABASE_USERNAME', '');
define('DATABASE_PASSWORD', '');

?>
