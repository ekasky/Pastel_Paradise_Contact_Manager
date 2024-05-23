<?php

require_once "utils.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set the response headers to be JSON
header('Content-type: application/json');

// Ensure the incoming request is a POST request
if(!is_post_req()) exit();

// Get JWT from request header

// Validate the token
$token              = get_jwt();
$id                 = validate_token($token);

if($id === false) {

    http_response_code(401);

    echo json_encode([
        'error' => 'Invalid token'
    ]);

    exit();

}

// Get the request body
$body = get_request_body();

// Ensure all the required fields were supplied in the body
$required       = ['first_name', 'last_name', 'phone_number', 'email'];

if(($missing_fields = check_required_fields($required, $body)) !== true) {

    echo json_encode([
        'Missing' => implode(', ', $missing_fields)
    ]);

    exit();

}

// Connect to the database
$conn = connect_db();

if($conn === false) exit();

// Insert the new contact to the db
$query                  = 'INSERT INTO Contacts (first_name, last_name, phone_number, email, user_id) VALUES (?,?,?,?,?)';
$statment               = $conn->prepare($query);

$statment->bind_param("ssssi", $body['first_name'], $body['last_name'], $body['phone_number'], $body['email'], $id);

$result                 = $statment->execute();

if(!$result) {

    http_response_code();

    echo json_encode([
        'error' => 'Could not create new contact'
    ]);

    exit();

}

echo json_encode([
    'message' => 'Contact Created'
]);

// close the db connection
$statment->close();
$conn->close();

?>