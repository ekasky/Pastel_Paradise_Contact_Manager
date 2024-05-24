<?php

function connect_to_db() {

    $DB_HOST            = getenv('DB_HOST');
    $DB_USER            = getenv('DB_USER');
    $DB_PASS            = getenv('DB_PASS');
    $DB_NAME            = getenv('DB_NAME');

    // Attempt to connect to the db
    $conn               = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

    // Check if the connection was sucessful
    if($conn->connect_error) {

        error_log("[ERROR] Database connection failed: " . $conn->connect_error);
        return null;

    }

    return $conn;

}

?>