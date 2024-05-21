<?php

require_once '/var/www/html/vendor/autoload.php';

use \Firebase\JWT\JWT;

// Set headers for response
header('Content-Type:application/json');

// Ensure the request is a POST request
if($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'error' => 'This endpoint only accepts POST requests'
    ]);

    exit();

}

// Connect to the database
$conn = new mysqli('db', 'user', 'password', 'contacts');

if($conn->connect_errno) {

    echo json_encode([
        'error' => 'Could not connect to db'
    ]);

    exit();

}

// Ensure all the required fields have been sent in the body
$request_body = json_decode(file_get_contents('php://input'), true);
$error_msg = [];

if( !isset($request_body['username']) || empty($request_body['username']) ) {
    $error_msg[] = 'Username';
}

if( !isset($request_body['password']) || empty($request_body['password']) ) {
    $error_msg[] = 'Password';
}

if(count($error_msg) > 0) {

    echo json_encode([
        'error' => 'Missing fields: '.implode(', ', $error_msg)
    ]);

    exit();

}

// Extract body into variables
$username           = $request_body['username'];
$password           = $request_body['password'];

// Find the user in the db to ensure they have a account
$query = 'SELECT * FROM users WHERE username=?';

$statment = $conn->prepare($query);
$statment->bind_param("s", $username);
$statment->execute();

$result = $statment->get_result();

if($result->num_rows <= 0) {

    echo json_encode([
        'error' => 'User does not exists'
    ]);

    exit();

}

// Get the user record
$user = $result->fetch_assoc();

// Compare passwords to ensure authenticity of user

$verify = password_verify($password, $user['password']);

if(!$verify) {

    echo json_encode([
        'error' => 'Invalid credientials'
    ]);

    exit();

}

// Generate a jwt for the user using the FirebaseJWT lib
$key = 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u1v2w3x4y5zAaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789!@#$%^&*()';
$payload = [
    'id' => $user['id'],
    'username' => $user['username'],
    'exp' => time() + 3600
];

$jwt = JWT::encode($payload, $key, 'HS256');

echo json_encode(['token' => $jwt]);

?>