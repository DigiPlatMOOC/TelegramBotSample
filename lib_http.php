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
        Logger::error("Curl returned error $errno: $error", __FILE__);

        curl_close($handle);

        return false;
    }

    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));

    curl_close($handle);

    if ($http_code >= 500) {
        Logger::warning('Internal server error', __FILE__);
        return false;
    }
    else if($http_code == 401) {
        Logger::warning('Unauthorized request (check token)', __FILE__);
        return false;
    }
    else if ($http_code != 200) {
        Logger::warning("Request failure with code $http_code ($response)", __FILE__);
        return false;
    }
    else {
        return $response;
    }
}
