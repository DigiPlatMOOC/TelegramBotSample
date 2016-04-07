<?php
/**
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Support library. Don't change a thing here.
 */

include ("config.php");

/**
 * Mixes together parameters for an HTTP request.
 * @param array $orig_params Original parameters or null.
 * @param array $add_params Additional parameters or null.
 * @return array Final mixed parameters.
 */
function prepare_parameters($orig_params, $add_params) {
    if(!$orig_params || !is_array($orig_params)) {
        $orig_params = new array();
    }

    if($add_params && is_array($add_params)) {
        foreach ($add_params as $key => &$val) {
            $orig_params[$key] = $val;
        }
    }

    return $orig_params;
}

/**
 * Performs a cURL request and returns the expected response as string.
 * @param object Handle to cURL request.
 * @return string | false Response as text or false on failure.
 */
function perform_curl_request($handle) {
    $response = curl_exec($handle);

    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl returned error $errno: $error");

        curl_close($handle);

        return false;
    }

    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));

    curl_close($handle);

    if ($http_code >= 500) {
        error_log('Internal server error');
        return false;
    }
    else if($http_code == 401) {
        error_log('Unauthorized request (check token)');
        return false;
    }
    else if ($http_code != 200) {
        error_log("Request failure with code $http_code");
        return false;
    }
    else {
        return $response;
    }
}

/**
 * Performs a cURL request to a Telegram API and returns the parsed results.
 * @param object Handle to cURL request.
 * @return object | false Parsed response object or false on failure.
 */
function perform_telegram_request($handle) {
    $response = perform_curl_request($handle);
    if($response === false) {
        return false;
    }

    // Everything fine, return the result as object
    $response = json_decode($response, true);
    return $response['result'];
}

/**
 * Prepares an API request using cURL.
 * Returns a cURL handle, ready to perform the request, or false on failure.
 *
 * @param string $url HTTP request URI.
 * @param string $method HTTP method ('GET' or 'POST').
 * @param array $parameters Query string parameters.
 * @param mixed $body String or array of values to be passed as request payload.
 * @return object | false cURL handle or false on failure.
 */
function prepare_curl_api_request($url, $method, $parameters, $body) {
    // Parameter checking
    if(!is_string($url)) {
        error_log('URL must be a string');
        return false;
    }
    if($method !== 'GET' && $method !== 'POST') {
        error_log('Method must be either GET or POST');
        return false;
    }
    if(!$parameters) {
        $parameters = new array();
    }
    if(!is_array($parameters)) {
        error_log('Parameters must be an array of values');
        return false;
    }

    // Non-simple parameters (i.e., arrays) are encoded as JSON strings
    foreach ($parameters as $key => &$val) {
        if (!is_numeric($val) && !is_string($val)) {
            $val = json_encode($val);
        }
    }

    // Prepare final request URL
    $final_url = $url . '?' . http_build_query($parameters);

    // Prepare cURL handle
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    if($method === 'POST') {
        curl_setopt($handle, CURLOPT_POST, true);
    }

    return $handle;
}

/**
 * Sends a Telegram bot message.
 * https://core.telegram.org/bots/api#sendmessage

 * @param int $chat_id Identifier of the Telegram chat session.
 * @param string $message Message to send.
 * @param array $parameters Additional parameters that match the API request.
 * @return object | false Parsed JSON object returned by the API or false on failure.
 */
function telegram_send_message($chat_id, $message, $parameters) {
    prepare_parameters($parameters, new array(
        'chat_id' => $chat_id,
        'text' => $message
    ));

    $handle = prepare_curl_api_request(TELEGRAM_API_URI_MESSAGE, 'POST', $parameters, null);
    if($handle === false) {
        error_log('Failed to prepare cURL handle');
        return false;
    }

    return perform_telegram_request($handle);
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}

function alicerequest($chat_id,$text)
{
	$handle = curl_init(ALICE_URL);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
    curl_setopt($handle, CURLOPT_POST, TRUE);

	$requestString = "say=".$text."&bot_id=3&format=json&convo_id=" . $chat_id;
	echo $requestString;

    curl_setopt($handle, CURLOPT_POSTFIELDS, $requestString);
    curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded","Accept: application/json"));

    $response = curl_exec($handle);

    if ($response === false) {
      $errno = curl_errno($handle);
      $error = curl_error($handle);
      error_log("Curl returned error $errno: $error\n");
      curl_close($handle);
      return false;
    }

    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);

    if ($http_code == 200) {
		$responseBody = json_decode($response, true);
		$response = $responseBody['botsay'];
	}
	else {
		$response = "???";
	}

    return $response;
}
?>
