<?php
/*
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Support library. Don't change a thing here.
 */

include ("config.php");

/*
 * Performs a CURL request and returns the expected JSON response as an object.
 * Returns false on failure.
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
        error_log('Invalid access token');
        return false;
    }
    else if ($http_code != 200) {
        $response = json_decode($response, true);
        error_log("Request has failed with error {$response['error_code']}: {$response['description']}");
        return false;
    }
    else {
        // Everything fine, return the result as object
        $response = json_decode($response, true);
        return $response['result'];
    }
}

/*
 * Prepares an API request using cURL.
 * Returns a cURL handle, ready to perform the request, or false on failure.
 *
 * @url (string) HTTP request URI.
 * @method (string) HTTP method.
 * @parameters (array) Query string parameters.
 * @body (mixed) String or array of values to be passed as request payload.
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
