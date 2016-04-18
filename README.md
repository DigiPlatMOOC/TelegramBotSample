# Telegram Bot Sample

Simple Telegram bot backend template in PHP (works both in *pull* and in *push* mode).

You can use this code as a starting point for your own bot and add your own intelligence through custom PHP code, external services (perhaps an Alice AIML interpreter?), or anything else.

Have fun!

## Installation

You need:

* **PHP:** in order to run samples and make your bot work in *pull* mode.
* **Web server:** in order to serve requests by Telegram, in *push* mode (Apache, Nginx, or anything else, really).

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
Try, for instance, to launch the `php pull.php` script with long polling and *then* to send a message to your bot.
The script should fetch the new update and terminate as soon as the message is delivered to Telegram.

### Sending messages

Once you receive a message from a user (notice that Telegram bot conversations always start with the user sending a `/start` command to the bot), you also receive a chat identifier (use the `chat_id` attribute of your received message object).
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

Telegram bot conversations can also work in *push* mode: instead of making your code constantly fetch updates from Telegram server, it is the service itself that calls your code and activates your bot.
This simplifies your code and also allows you to eschew constant connections to the Telegram API.

However, you'll need the following additional things:

* A domain name: that is, your web server must answer to a public domain name (i.e., my-web-server.com). You can buy a domain name and an associated hosting plan very cheaply nowadays.
* A certificate, associated to the domain name above. This can be costly, but you can also look into projects like [Let's encrypt](https://letsencrypt.org).

If you satisfy both criteria, you can setup push delivery by running the `register-bot.sh` script:

```
chmod +x register-bot.sh
./register-bot.sh -t BOT-TOKEN -c /path/to/public/certificate.pem -s https://my-web-server.com/hook.php
```

Replace the `-t` parameter with your bot's actual token and the `-c` parameter with the path to your certificate (either a PEM or a CRT file).
The `-s` parameter should be an HTTPS URI pointing to the `hook.php` file on your web server.

Once the "web hook" has been setup, Telegram will automatically call your `hook.php` file whenever a message is received.

(You can turn off any registered web hook by running the `unregister-bot.sh` script and passing in the bot's token.)

## Message processing

Both the `pull.php` and the `push.php` scripts receive messages from Telegram and process them by calling into the `process_message` function in `lib_msg_processing.php`.
The function takes a new message as input and includes some boilerplate code for you to fill out:

```php
function process_message($message) {
    // $message = {
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
    $message_text = $message['text'];

    // TODO: put message processing logic here
}
```

Notice that the `$message_text` variable can be `null` if the message does not contain any text (for instance, if the user did send a location or an image).

You can easily create an "echo bot" (that simply responds by sending back the original text to the user) by calling a library function:

```php
telegram_send_message($chat_id, $message_text, null);
```

Also, you can easily detect Telegram commands (which, by convention, start with a slash character, like `/start`) using `strpos`:

```php
if (strpos($text, "/start") === 0) {
    // Received a /start command
}
```

### Connecting with an AIML bot

An easy way to add some kind of natural language processing intelligence to your bot, is to make use of an AIML interpreter, like—for instance—[Program-O](http://www.program-o.com).
This open-source AIML interpreter also exposes a [public API](http://www.program-o.com/chatbotapi) that you can very easily hook up to your Telegram bot:

```php
// Send text by user to AIML bot
$handle = prepare_curl_api_request('http://api.program-o.com/v2/chatbot/', 'POST',
    array(
        'say' => $message_text,
        'bot_id' => 6,
        'format' => 'json',
        'convo_id' => 'uwiclab-bot-sample'
    ),
    null,
    array(
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json'
    )
);
$response = perform_curl_request($handle);

// Response has the following JSON format:
// {
//     "convo_id" : "5uf2nupqmb",
//     "usersay": "MESSAGE FROM USER",
//     "botsay" : "Response by bot"
// }
$json_response = json_decode($response, true);
$bot_response = $json_response['botsay'];

// Send AIML bot response back to user
$response = telegram_send_message($chat_id, $bot_response, null);
```

In order to customize your bot's intelligence, you'll have to download Program-O, install it locally to your server (this software requires PHP and MySQL), and then hook it up to your Telegram bot web hook.
By providing one or more AIML files to the Program-O interpeter, you'll be able to have an *almost* natural conversation with your bot in no time.
