<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Configuration file.
 */

define('PROGRAM_O_API_URI','http://chatbot.clouduino.eu/klopfenstein/programo/chatbot/conversation_start.php');
define('TELEGRAM_BOT_TOKEN', '148168117:AAGV1XjYNEloMdoaf6V2_BYX8YeELgKL_9U');
define('TELEGRAM_API_URI_BASE', 'https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/');
define('TELEGRAM_API_URI_MESSAGE', TELEGRAM_API_URI_BASE . 'sendMessage');

?>
