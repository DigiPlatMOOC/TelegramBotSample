# Telegram Bot Sample

Simple Telegram bot backend template in PHP (works both in *pull* and in *push* mode).

You can use this code as a starting point for your own bot and add your own intelligence through PHP code, external services (perhaps an Alice AIML interpreter?), or anything else.

Have fun!

## Installation

You need:

* **The PHP interpreter:** sufficient in order to run samples and make your bot work in *pull* mode.
* **A web server:** in order to serve requests by Telegram, in *push* mode.

First of all, create a new Telegram Bot, by chatting with the [BotFather](http://telegram.me/BotFather). Your bot will need a unique **nickname** and you will obtain a unique **token** in return.
This token is all you need to communicate with the bot through the Telegram API.

Edit the `config.php` file and set the `TELEGRAM_BOT_TOKEN` constant with your token.

## Interacting with your bot

### Receiving messages (pull)

Once the bot's token has been set, you can very easily fetch new messages (one by one) using the `pull.php` script.

```
php pull.php
```

This will retrieve new messages (if any) and print out the JSON data to console.

Notice that Telegram keeps a queue of received and delivered messages on its servers.
If no particular message is queried, Telegram may return the same message over and over.
In order to advance through the queue of messages to deliver, the `pull.php` script keeps track of the last update received—by storing the update's ID—and by performing a query for the *next* update in queue. (See line 20.)
The last update ID is stored in the `pull-last-update.txt` file.

Also, Telegram's `getUpdates` query can perform **[long polling](https://core.telegram.org/bots/api#getupdates)**, which stalls your request until an update is ready to be delivered (or until the request times out).
In order to make use of this feature, modify line 20 as follows:

```php
$content = telegram_get_updates(intval($last_update) + 1, 1, 120);
```

where the third parameter, `120`, is the maximum number of seconds to wait for the request to return (i.e., 2 minutes).

### Sending messages

Once you receive a message from a user (notice that bot conversations always start with the user sending a `/start` command to the bot), you also receive a chat identifier (use the `chat_id` attribute of your received message object).
This identifier can be used to send messages back to the user.

In order to do so, use the following function:

```php
$response = telegram_send_message(CHAT_ID, "Hello user!", null);
```

Check out the script `send.php` for a complete example (you'll have to fill in an existing chat ID at line 13), that allows to send messages through a command line parameter:

```
php send.php "This is the text to send!"
```

### Receiving messages (push)

Coming soon

## Message processing

Coming soon
