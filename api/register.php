<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'middleware.php';

header('Content-Type:application/json');

try {

    
    valid_request_type('POST');                             // Ensure request is POST. Throws a 405 Method Not Allowed error if incorrect method

    $conn = connect();                                      // Create a connection to the database. Throws a 500 Internal Server Error code if error occurs connecting to db.
    $body = get_request_body();                             // Get the request body. Throws a 400 Bad Request if error occurs when getting the request body.
    
    required_request_fields([
        'first_name',
        'last_name',
        'email',
        'password',
        'confirm_password'
    ], $body);                                              // Checks if all the fields supplied in array are present in request body. Throws a 400 Bad Request in a field is missing


    // Exract the request fields from the body
    $first_name       = $body['first_name'];
    $last_name        = $body['last_name'];
    $email            = $body['email'];
    $password         = $body['password'];
    $confirm_password = $body['confirm_password'];

    valid_email($email);                                                    // Validates email is in proper form using regex. If the pattern does not match it throws a 400 Bad Request code
    passwords_match($password, $confirm_password);                          // If the two passwords supplied in the body do not match it throws a 400 Bad Request code
    valid_password($password);                                              // Validates password is strong enough. If not it throws a 400 Badf Request code.
    
    if(find_user_by_email($email, $conn) !== null) {
        throw new Exception('Email is already in use', 409);                // If a user is found, return a 409 Conflict error.
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);                      // Hash the password for safe storage

    insert_into_users($first_name, $last_name, $email, $hash, $conn);       // Inserts new user into the Users table. If there is a error a 500 Internal Server Error code is thrown

    $conn->close();

    http_response_code(201);                                                // Return 201 Created Code

    echo json_encode([
        'message' => 'User created successfully'
    ]);

}
catch(Exception $e) {

    http_response_code($e->getCode());

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}

?>