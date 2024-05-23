<?php

require_once "../utils.php";

// Set the response headers to be JSON
header('Content-type: application/json');

// Ensure the incoming request is a POST request
if(!is_post_req()) exit();

// Get the request body
$body = get_request_body();

// Ensure all the required fields were supplied in the body
$required       = ['first_name', 'last_name', 'username', 'password'];

if(($missing_fields = check_required_fields($required, $body)) !== true) {

    echo json_encode([
        'Missing' => implode(', ', $missing_fields)
    ]);

    exit();

}

// Connect to the database
$conn = connect_db();

if($conn === false) exit();

// See if the username is taken
if( find_user_by_username($body['username'], $conn) !== false ) {

    http_response_code(409);            // Conflicting record found

    echo json_encode([
        'error' => 'Username taken'
    ]);

    exit();

}

// Hash the password for secure storage
$password           = password_hash($body['password'], PASSWORD_BCRYPT);

// Insert the new user to the database
$query              = 'INSERT INTO Users (first_name, last_name, username, password, created_at, last_login) VALUES (?,?,?,?,?,?)';
$date               = date('Y-m-d H:i:s');
$statment           = $conn->prepare($query);

$statment->bind_param("ssssss", $body['first_name'], $body['last_name'], $body['username'], $password, $date, $date);

$user               = $statment->execute();

if(!$user) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Could not create account'
    ]);

    exit();

}

echo json_encode([
    'message' => 'Account created'
]);

// Close db connection
$statment->close();
$conn->close();

?>