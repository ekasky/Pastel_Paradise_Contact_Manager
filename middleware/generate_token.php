<?php

require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generate_token($user, $exp_ms) {

    // Create the token payload
    $payload = [

        'iss'       => 'pastelparadise.xyz',
        'iat'       => time(),
        'exp'       => time() + $exp_ms,
        'id'        => $user['id'],
        'username'  => $user['username']

    ];

    // Get the secret key from the enviroment variables
    $key            = getenv('SECRET_KEY');

    // Try and encode the jwt
    try {

        $jwt        = JWT::encode($payload, $key, 'HS256');

        return $jwt;

    }
    catch(Exception $e) {

        error_log('[ERROR] Could not encode JWT: ' . $e->getMessage());
        return null;

    }

}

?>