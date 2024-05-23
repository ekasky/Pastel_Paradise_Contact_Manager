<?php

require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function validateToken($jwt) {

    try {

        $key = getenv('SECRET_KEY');
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        $id = $decoded->id;
        return $id;

    } catch (Exception $e) {

        return false;
        
    }

}

?>