<?php

require_once "../utils.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set the response headers to be JSON
header('Content-type: application/json');

// Ensure the incoming request is a POST request
if(!is_post_req()) exit();

// Validate the token
$token              = get_jwt();
$user_id            = validate_token($token);

if($user_id === false) {

    http_response_code(401);

    echo json_encode([
        'error' => 'Invalid token'
    ]);

    exit();

}

// Get the request body
$body = get_request_body();

// Ensure all the required fields were supplied in the body
$required       = ['id'];

if(($missing_fields = check_required_fields($required, $body)) !== true) {

    echo json_encode([
        'Missing' => implode(', ', $missing_fields)
    ]);

    exit();

}

// Connect to the database
$conn = connect_db();

if($conn === false) exit();

// Check if the contact exists ans belongs to the logged in user
$query          = 'SELECT * FROM Contacts WHERE id=? AND user_id=?';
$statment       = $conn->prepare($query);

$statment->bind_param("ii", $body['id'], $user_id);
$statment->execute();

$result         = $statment->get_result();

if($result->num_rows === 0) {

    http_response_code(404);

    echo json_encode([
        'error' => 'Contact not found'
    ]);
    
    exit();

}

// Delete contact from the db
$query          = 'DELETE FROM Contacts WHERE id=? AND user_id=?';
$statment       = $conn->prepare($query);

$statment->bind_param("ii", $body['id'], $user_id);
$statment->execute();

// Check to ensure the record was actually removed
if($statment->affected_rows === 0) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Failed to delete contact'
    ]);

    exit();

}

// Return success message
echo json_encode([
    'message' => 'Deleted id: ' . $body['id']
]);

// Close connection to db
$statment->close();
$conn->close();

?>