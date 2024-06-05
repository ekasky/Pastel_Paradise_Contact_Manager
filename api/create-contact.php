<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'middleware.php';

header('Content-Type:application/json');

try {

    valid_request_type('POST');                                         // Ensure request is POST. Throws a 405 Method Not Allowed error if incorrect method

    $conn = connect();                                                  // Create a connection to the database. Throws a 500 Internal Server Error code if error occurs connecting to db.

    $session_record = valid_session($conn);                             // Checks if session is valid, if not it throws a 401 Unauthorized code

    $body = get_request_body();                                         // Get the request body. Throws a 400 Bad Request if error occurs when getting the request body.

    required_request_fields([
        'first_name',
        'last_name',
        'email',
        'phone'
    ], $body);                                                         // Checks if all the fields supplied in array are present in request body. Throws a 400 Bad Request in a field is missing

    // Extract the request fields
    $first_name = $body['first_name'];
    $last_name  = $body['last_name'];
    $email      = $body['email'];
    $phone      = $body['phone'];
    $user_id    = $session_record['user_id'];

    valid_email($email);                                               // Validates email is in proper form using regex. If the pattern does not match it throws a 400 Bad Request code
    valid_phone($phone);                                               // Validates phone is in proper form using regex. If the pattern does not match it throws a 400 Bad Request code 

    insert_into_contacts($first_name, $last_name ,$email, $phone, $user_id, $conn);         // If it fails to inset a new user a 500 Internal Server Error is thrown

    http_response_code(200);

    echo json_encode([
        'message' => 'Contact successfully created'
    ]);

}
catch(Exception $e) {

    http_response_code($e->getCode());

    echo json_encode([
        'message' => $e->getMessage()
    ]);

}

?>