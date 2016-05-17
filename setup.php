<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Setup script: if you need database access in your Telegram bot,
 * ensure that the MySQL connection is properly configured in file
 * config.php and run this script once.
 */

include ('lib.php');

echo 'Hello! We will now configure your database...' . PHP_EOL;

$exist_table = db_scalar_query("SELECT 1 FROM `conversation`");
if($exist_table !== false) {
    echo 'The database has already been configured.' . PHP_EOL;
    exit;
}

$create_table = db_perform_action("CREATE TABLE `conversation` (
  `user_id` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
if($create_table === false) {
    echo 'Database creation failed.' . PHP_EOL;
    exit;
}

$alter_table = db_perform_action("ALTER TABLE `conversation` ADD UNIQUE KEY `user` (`user_id`);");
if($alter_table === false) {
    echo 'Failed to add index on table.' . PHP_EOL;
    exit;
}

echo 'All set.' . PHP_EOL;
