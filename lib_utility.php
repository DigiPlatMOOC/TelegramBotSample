<?php
/**
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Support library. Don't change a thing here.
 */

/**
 * Mixes together parameters for an HTTP request.
 *
 * @param array $orig_params Original parameters or null.
 * @param array $add_params Additional parameters or null.
 * @return array Final mixed parameters.
 */
function prepare_parameters($orig_params, $add_params) {
    if(!$orig_params || !is_array($orig_params)) {
        $orig_params = array();
    }

    if($add_params && is_array($add_params)) {
        foreach ($add_params as $key => &$val) {
            $orig_params[$key] = $val;
        }
    }

    return $orig_params;
}
