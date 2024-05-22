<?php

require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;

// Set the response headers to be JSON
header('Content-type: application/json');

// Check the incoming request type
if($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'message' => 'Only POST request allowed'
    ]);

    exit();

}

// Get the data from the request body
$respose_body = file_get_contents('php://input');
$body_json = json_decode($respose_body, true);

// Ensure all the required fields were supplied in the request body
$missing_fields = [];

if( !isset($body_json['username']) || empty($body_json['username']) ) {
    $missing_fields[] = 'Username';
}

if( !isset($body_json['password']) || empty($body_json['password']) ) {
    $missing_fields[] = 'Password';
}

if(count($missing_fields) > 0) {

    echo json_encode([
        'error' => 'Missing fields: ' . implode(', ', $missing_fields)
    ]);

}

// Extract each field
$username = $body_json['username'];
$password = $body_json['password'];

// Connect to the db
$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));

if($conn->connect_error) {

    echo json_encode([
        'error' => 'Username Taken'
    ]);

    exit();

}

// Find the users record in the db
$query = 'SELECT * FROM Users WHERE username=?';

$statment = $conn->prepare($query);
$statment->bind_param("s", $username);
$statment->execute();

$result = $statment->get_result();

if($result->num_rows <= 0) {

    echo json_encode([
        'error' => 'User not found'
    ]);

    exit();

}

// Fetch the user record
$user = $result->fetch_assoc();

// Check the password to see if they match whats on record
$verify = password_verify($password, $user['password']);


if(!$verify) {

    echo json_encode([
        'error' => 'invalid credentials'
    ]);

    exit();

}

// Generate JWT token
$payload = [
    'iss' => 'http://159.203.134.143/',
    'iat' => time(),
    'exp' => time() + 3600
];

try {
    // Attempt to encode JWT
    $jwt = JWT::encode($payload, getenv('SECRET_KEY'), 'HS256');

    // Output JWT token
    echo $jwt;
} catch (Exception $e) {
    // Handle JWT encoding error
    echo 'Error encoding JWT: ' . $e->getMessage();
}

// Send login token

echo $jwt;

// Close the db connection
$statment->close();
$conn->close();

?>