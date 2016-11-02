<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Configuration file.
 */

define('TELEGRAM_BOT_TOKEN', 'INSERT TOKEN HERE');
define('TELEGRAM_API_URI_BASE', 'https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/');
define('TELEGRAM_API_URI_ME', TELEGRAM_API_URI_BASE . 'getMe');
define('TELEGRAM_API_URI_MESSAGE', TELEGRAM_API_URI_BASE . 'sendMessage');
define('TELEGRAM_API_URI_EDIT_MESSAGE', TELEGRAM_API_URI_BASE . 'editMessageText');
define('TELEGRAM_API_URI_LOCATION', TELEGRAM_API_URI_BASE . 'sendLocation');
define('TELEGRAM_API_URI_PHOTO', TELEGRAM_API_URI_BASE . 'sendPhoto');
define('TELEGRAM_API_URI_UPDATES', TELEGRAM_API_URI_BASE . 'getUpdates');
define('TELEGRAM_API_URI_SEND_ACTION', TELEGRAM_API_URI_BASE . 'sendChatAction');

// Optional configuration: fill in if you use a MySQL database
// and make use of the library functions in lib_database.php
define('DATABASE_HOST', 'localhost');
define('DATABASE_NAME', '');
define('DATABASE_USERNAME', '');
define('DATABASE_PASSWORD', '');
