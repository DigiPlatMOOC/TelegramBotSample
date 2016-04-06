<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Basic message processing end-point for your bot.
 * Start editing here. =)
 */

include ("lib.php");

function processMessage($message)
{
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text']))
  	{
   	 	// incoming text message
  	  $text = $message['text'];
  	if (strpos($text, "/start") === 0)
		{
			apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'hei'));
   	 	}
		else if (strpos($text, "audio") === 0 || strpos($text, "manda un audio") === 0)
		{
			apiRequest("sendDocument", array('chat_id' => $chat_id, "document"   => "BQADBAADDAADr6qEB6PQYEUWsy5KAg"));
	    }
		else if (strpos($text, "foto") === 0 || strpos($text, "manda una foto") === 0)
		{
			apiRequest("sendPhoto", array('chat_id' => $chat_id, "photo"   => "AgADBAADtKcxG6-qhAc5-dBu7k__u2OLjzAABER-rxPmZC4lpC4CAAEC"));
	    }
   	 else
		{
	  	  $bot = alicerequest("telegram".$chat_id,$text);
      	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "$bot"));
    	}
    }
   else
   {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
   }
}

// Get contents from HTTP request
$content = file_get_contents("php://input");

// Decode contents as JSON
$update = json_decode($content, true);

if (!$update) {
    error_log('Bad message received (not JSON)');
    exit;
}
else {
    if (isset($update["message"])) {
        processMessage($update["message"]);
    }
    else {
        error_log('Bad message received (no message field)');
        exit;
    }
}
