<?php

require_once 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function get_user_from_token($jwt) {

    // Get the key from the env var
    $key = getenv('SECRET_KEY');

    // Decode the token
    try {

        $decoded        = JWT::decode($jwt, new Key($key, 'HS256'));

        return json_encode([
            'id' => $decoded->id,
            'email' => $decoded->email
        ]);

    }
    catch(Exception $e) {

        error_log('[ERROR] Could not get user info from token: ' . $e->getMessage());
        return null;

    }

}

?>