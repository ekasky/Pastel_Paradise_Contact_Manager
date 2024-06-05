<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'middleware.php';

header('Content-Type:application/json');

try {

    valid_request_type('POST');                                     // Ensure request is POST. Throws a 405 Method Not Allowed error if incorrect method
    
    $conn = connect();                                              // Create a connection to the database. Throws a 500 Internal Server Error code if error occurs connecting to db.
    $body = get_request_body();                                     // Get the request body. Throws a 400 Bad Request if error occurs when getting the request body.

    required_request_fields([
        'email',
        'password'
    ], $body);                                                      // Checks if all the fields supplied in array are present in request body. Throws a 400 Bad Request in a field is missing

    // Exract the request fields from the body
    $email            = $body['email'];
    $password         = $body['password'];

    if(($user = find_user_by_email($email, $conn)) === null) {
        throw new Exception('Invalid login credentials', 401);      // If no user if found with the email, return a 401 code Unauthorized
    }

    verfiy_password($password, $user['password']);                  // If the password supplied does not match the password on file throw 401 Unauthorized

    $ssid = generate_session_id();                                  // Creates 64-byte random id
    create_session_cookie($ssid);                                   // Creates a session cookie for the user's session
    create_user_session($ssid, $user['id'], $conn);                 // Adds the session to the Sessions table. Returns a 500 Internal Server Error if something goes wrong

    http_response_code(200);

    echo json_encode([
        'message' => 'User logged in successfully'
    ]);

}
catch(Exception $e) {

    http_response_code($e->getCode());

    echo json_encode([
        'message' => $e->getMessage()
    ]);

}

?>