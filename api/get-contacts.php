<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'middleware.php';

header('Content-Type:application/json');

try {

    valid_request_type('GET');                                     // Ensure request is GET. Throws a 405 Method Not Allowed error if incorrect method
    $conn           = connect();                                   // Create a connection to the database. Throws a 500 Internal Server Error code if error occurs connecting to db.
    $session_record = valid_session($conn);                        // Checks if session is valid, if not it throws a 401 Unauthorized code
    $user_id        = $session_record['user_id'];                  // Extract the user's id from the session record
    
    $contacts = get_users_contacts($user_id, $conn);               // Gets all users contacts from db. If no contacts are found then it returns 404 Not Found code and if a db query error occurs it throws 500 Internal Server Error

    http_response_code(200);

    echo json_encode([
        'message' => $contacts
    ]);

}
catch(Exception $e) {

    http_response_code($e->getCode());

    echo json_encode([
        'message' => $e->getMessage()
    ]);

}

?>