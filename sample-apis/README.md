# API samples

This folder includes sample APIs that you can use with your Telegram bot.

Unless otherwise states, they can be used directly from the command line interface.

## Random.org

This script simulates a *true* random dice roll using the new APIs of the [Random.org service](https://api.random.org/features).
You will first need to supply your own Random.org API key at line 12:

```php
define('RANDOM_ORG_API_KEY', 'your API key here');
```

You can request a [new API key online](https://api.random.org/api-keys/beta).

## Program-O

[Program-O](http://www.program-o.com) is an open-source AIML interpreter that can be used to create an “intelligent” chatty bot agent.
The official web-site provides an [online test API](http://www.program-o.com/chatbotapi) that can be used with one of the sample bots.

You can chat directly from the command line:

```
php program-o.php "Hello!"
```

Which will print:

```
http://api.program-o.com/v2/chatbot/?say=Hello%21&bot_id=6&format=json&convo_id=uwiclab-bot-sample
Response from Program-O bot: {"convo_id":"uwiclab-bot-sample","usersay":"HELLO","botsay":"Hi there!"}
You said: HELLO
Bot says: Hi there!
```

The same PHP code that establishes a connection to the remote Program-O API can be tweaked in order to connect to a local Program-O installation.
Program-O can be [downloaded and forked on Github](https://github.com/Program-O/Program-O).

## Bing reverse geocoding

When a Telegram user shares his or her position with your bot, your bot will receive a pair of latitude and longitude coordinates.
This format can be easily stored in a database and is the most common interchange format for geo-position (thus it will be compatible with most other APIs you may find useful).

However, sometimes you need an **address** and not a pair of coordinates.
For instance in order to describe a position to a human user.
The process of converting a geographical coordinate into a “human” address is called **reverse geocoding** and can be performed by a variety of online APIs.

The sample code supplied uses the [Bing Maps API](https://msdn.microsoft.com/en-us/library/ff817004.aspx) and can be used freely (within some limits) by anyone, provided that you first [generate an API key on the Bing Maps Portal](https://www.bingmapsportal.com).
Your fresh API key must be pasted at line 10 of the file:

```php
define('BING_API', 'your API key here');
```

You will now be able to query the address of any geographical coordinate, just by launching the script and passing latitude and longitude as command line parameters:

```
php bing-rev-geo.php 43.726480 12.637148
```

The Bing Maps API returns a quite complex set of results, but you can easily extract the “locality” (or some other information) from the returned address.
