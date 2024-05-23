<?php

require_once "../utils.php";

// Set the response headers to be JSON
header('Content-type: application/json');

// Ensure the incoming request is a POST request
if(!is_post_req()) exit();

// Get the request body
$body = get_request_body();

// Ensure all the required fields were supplied in the body
$required       = ['username', 'password'];

if(($missing_fields = check_required_fields($required, $body)) !== true) {

    echo json_encode([
        'Missing' => implode(', ', $missing_fields)
    ]);

    exit();

}

// Connect to the database
$conn = connect_db();

if($conn === false) exit();

// Get the users record if it exists
$user           = find_user_by_username($body['username'], $conn);

if($user === false) {

    echo json_encode([
        'error' => 'User not found'
    ]);

    http_response_code(404);            // User not found

    exit();

}

// Compare the password supplied with the users record
$verify         = password_verify($body['password'], $user['password']);

if(!$verify) {

    http_response_code(401);

    echo json_encode([
        'error' => 'Invalid Credentials'
    ]);

    exit();

}

// Generate the users jwt
$token = generate_token($user);

setcookie("token", $token, time() + 3600, "/");

echo json_encode([
    'token' => $token
]);

// Close the db connection
$conn->close();

?>