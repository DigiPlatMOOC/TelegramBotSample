<?php
/**
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Support library. Don't change a thing here.
 */

/**
 * Performs a cURL request and returns the expected response as string.
 *
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
        error_log("Request failure with code $http_code ($response)");
        return false;
    }
    else {
        return $response;
    }
}
