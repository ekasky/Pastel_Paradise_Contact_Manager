<?php

require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function validate_token($jwt) {

    try {

        $key            = getenv('SECRET_KEY');
        $decoded        = JWT::decode($jwt, new Key($key, 'HS256'));
        $id             = $decoded->id;

        return $id;

    } catch (Exception $e) {

        return false;
        
    }

}

function connect_db() {

    $db_host            = getenv('DB_HOST');
    $db_user            = getenv('DB_USER');
    $db_pass            = getenv('DB_PASS');
    $db_name            = getenv('DB_NAME');

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check if the connection was sucessfull
    if($conn->connect_error) {

        return false;

    }

    return $conn;

}

function check_required_fields($required_fields, $supplied_fields) {

    $missing_fields = [];

    // Loop through all the required fields and track if any are missing
    foreach($required_fields as $field ) {

        if( !isset($supplied_fields[$field]) || empty($supplied_fields[$field]) ) {

            $missing_fields[] = $field;

        }

    }

    // If the length of the missing_fields array is not 0 one of the required fields was not supplied
    if(count($missing_fields) > 0) {
        return $missing_fields;
    }

    return true;

}

?>