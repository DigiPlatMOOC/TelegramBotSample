<?php
/**
 * Telegram Bot Sample
 * ===================
 * UWiClab, University of Urbino
 * ===================
 * Database support library. Don't change a thing here.
 */

/**
 * Closes the existing connection to the database, if any.
 */
function db_close_connection() {
    if(isset($GLOBALS['db_connection'])) {
        $connection = $GLOBALS['db_connection'];
        
        mysqli_close($connection);
    }
    
    unset($GLOBALS['db_connection']);
}

/**
 * Creates or retrieves a connection to the database.
 * @return object A valid connection to the database.
 */
function db_open_connection() {
    if(isset($GLOBALS['db_connection'])) {
        $connection = $GLOBALS['db_connection'];
        
        //Ping the connection just to be safe
        //This can be removed for performance since we usually have no
        //long-running scripts.
        if(!mysqli_ping($connection)) {
            error_log('Database connection already open but does not respond to ping');
            die();
        }
        
        return $connection;
    }
    else {
        //Open up a new connection
        $connection = mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
        
        if(!$connection) {
            $errno = mysqli_connect_errno();
            $error = mysqli_connect_error();
            
            error_log("Failed to establish database connection. Err #$errno: $error");
            die();
        }
        
        //Store connection for later
        $GLOBALS['db_connection'] = $connection;
        
        //Register clean up function for termination
        register_shutdown_function('db_close_connection');
        
        return $connection;
    }
}

?>