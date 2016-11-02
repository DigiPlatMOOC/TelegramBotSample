# Telegram Bot Sample

Simple Telegram bot backend template, written in PHP.
Works both in *pull* and in *push* mode.

You can use this code as a starting point for your own bot and add your own intelligence through custom PHP code, external services (perhaps an Alice AIML interpreter?), or anything else.

Have fun!

## Installation

You need:

* **PHP:** in order to run samples and make your bot work in *pull* mode.
* **A web server and a domain:** in order to serve requests by Telegram in *push* mode (Apache, Nginx, or anything similar, really).

First of all, create a new Telegram Bot, by chatting with the [BotFather](http://telegram.me/BotFather). Your bot will need a unique **nickname** and you will obtain a unique **token** in return.
This token is all you need to communicate with the bot through the Telegram API.

Edit the `config.php` file and set the `TELEGRAM_BOT_TOKEN` constant with your token.

## Interacting with your bot

### Receiving messages (pull)

Once the bot's token has been set, you can very easily fetch new messages (one by one) using the `pull.php` script.

```
php pull.php
```

This will retrieve new messages (if any) and print out the JSON data to standard output by default.

Notice that Telegram keeps a queue of received and delivered messages on its servers.
If no particular message is queried, Telegram may return the same message over and over.
In order to advance through the queue of messages to deliver, the `pull.php` script keeps track of the last update received—by storing the update's ID—and by performing a query for the *next* update in queue. (See line 20.)
The last update ID is stored in the `pull-last-update.txt` file.

Also notice that Telegram's `getUpdates` query can perform **[long polling](https://core.telegram.org/bots/api#getupdates)**, which stalls your request until an update is ready to be delivered (or until the request times out).
By default the `pull.php` will wait for as long as *60 seconds* for an update (see line 20).

In order to turn off this feature and switch to immediate pulling, set the third parameter at line 20 as follows:

```php
$content = telegram_get_updates(intval($last_update) + 1, 1, 0);
```

In this case the request will return right away and your script will terminate.
Try launching `php pull.php` with different settings, either sending a message to your bot *before* or *after* launching the script.

If the `pull.php` script is configured to use *long-polling*, it can also be launched in continuous polling, using the `continuous-poll.sh` shell script.
The script does nothing else except running the pull script over and over, thus effectively keeping your Telegram bot alive and working without interruptions, even if for some reason you cannot run your bot in *push* mode (see below).

### Sending messages

Once you receive a message from a user (notice that Telegram bot conversations always start with the user sending a `/start` command to the bot), you also receive a **chat identifier** (use the `chat_id` attribute of your received message object).
This identifier can be used to send messages back to the user.

In order to do so, use the following function:

```php
$response = telegram_send_message(CHAT_ID, "Hello user!", null);
```

Check out the script `send.php` for a complete example (you'll have to fill in an existing chat ID at line 14), that allows to send messages through a command line parameter:

```
php send.php "This is the text to send!"
```

Also, take a look to the `lib.php` file, which includes many other library functions you can use to send messages, photos, and locations through the Telegram API.

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

Once the "web hook" has been registered, Telegram will automatically call your `hook.php` file whenever a message is received.

(You can turn off any registered web hook by running the `unregister-bot.sh` script and passing in the bot's token.)

## Message processing

Both the `pull.php` and the `hook.php` scripts receive messages from Telegram and process them by including the `msg_processing_simple.php` script.
The script assumes that a new message was received (stored as the `$message` variable) and includes some boilerplate code for you to fill out:

```php
// Assumes incoming $message object
$message_id = $message['message_id'];
$chat_id = $message['chat']['id'];
$message_text = $message['text'];

// TODO: put message processing logic here
```

Notice that the `$message_text` variable can be `null` if the message does not contain any text (for instance, if the user sent a location or an image instead of a text message).

You can easily create an "echo bot" (that simply responds by sending back the original text to the user) by adding this call to the library function:

```php
telegram_send_message($chat_id, $message_text);
```

Also, you can easily detect Telegram commands (which, by convention, start with a slash character, like `/start`) using `strpos`:

```php
if (strpos($text, "/start") === 0) {
    // Received a /start command
}
```

Take a look to the `msg_processing_simple.php` script for a general idea of how message processing looks like.
By adding logic you can start adding some kind of *intelligence* to your bot.

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

Check out `sample-apis/program-o.php` for a stand-alone sample.

### Conversations and Program-O

When linking a Telegram bot and a Program-O bot to provide a, seemingly, intelligent conversation with your users, it is important for the Program-O bot to correctly identify the user it is talking to.

As you may have noticed, the Program-O API provides a `convo_id` parameter.
This parameter identifies the conversation to the Program-O bot and allows it to distinguish between different users and different chats.

In the example above we simply used *one* conversation ID for every incoming message (namely, “uwiclab-bot-sample”).
This, however, means that every user talking with our Program-O bot shares the same conversation and also the same memory.
Any information stored about the user thus applies to *every* user of your bot.

This can be easily fixed: just make sure to provide a different `convo_id` parameter for every Telegram conversation.
For instance, by altering the code above as follows:

```php
$handle = prepare_curl_api_request('http://api.program-o.com/v2/chatbot/', 'POST',
    array(
        'say' => $message_text,
        'bot_id' => 6,
        'format' => 'json',
        'convo_id' => "telegram-$chat_id"
    ),
    null,
    array(
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json'
    )
);
```

In this way, a message on Telegram chat #123123123 will be forwarded to Program-O using the `telegram-123123123` conversation ID.
This keeps every Telegram chat well separated from the others.

## The bot's memory: using a database

As long as you let Program-O drive your Telegram bot, there actually is no need to take care of the bot's memory: the Program-O interpreter does all the work for you.

However, if you should choose to write your own bot logic in PHP, you'll need a persistent storage to keep information about your users and about the conversations they are having.
That is, you'll need a **database**.

Make sure you edit the `config.php` and provide the correct database connection credentials.

```php
define('DATABASE_HOST', 'localhost');
define('DATABASE_NAME', '');
define('DATABASE_USERNAME', '');
define('DATABASE_PASSWORD', '');
```

The constants above are used by the `lib_database.php` script, that provides several useful functions for using a database from your code.

Once correctly setup, launch the database setup script.
(This needs to be done only *once*.)

```
php setup.php
```

Your database will be updated with a `conversation` table, which allows your bot to keep track of the state of its conversations.
In particular, state is stored as a simple integer, matching the ID of the user talking to the bot.

Switch to the `msg_processing_conversation.php` script in your `pull.php` (or `hook.php`) file and check out how the conversational message processing works.

## Help!

Any questions?
Send us an e-mail or open an issue here on Github and we'll be glad to help.
