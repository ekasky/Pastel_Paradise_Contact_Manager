<?php


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function validate_token($jwt) {

    // Get the key from the env vars
    $key                = getenv('SECRET_KEY');

    // Try and decode the provied token
    try {

        $decoded        = JWT::decode($jwt, $key, ['HS256']);

        return true;

    }
    catch(Exception $e) {

        error_log('[ERROR] Could not validate the token: ' . $e->getMessage());
        return false;

    }

}

?>