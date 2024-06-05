<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'middleware.php';

header('Content-Type:application/json');

try {

    valid_request_type('DELETE');                                     // Ensure request is DELETE. Throws a 405 Method Not Allowed error if incorrect method

    $conn = connect();                                                // Create a connection to the database. Throws a 500 Internal Server Error code if error occurs connecting to db.

    valid_session($conn);                                             // Ensure the user has a valid session

    destroy_session($conn);                                           // Attempts to remove user's session. If there is no session it returnd a 404 not found, if there is a db error it throws 500 Internal Server Error

    http_response_code(200);

    echo json_encode([
        'message' => 'User logged out successfully'
    ]);

}
catch(Exception $e) {

    http_response_code($e->getCode());

    echo json_encode([
        'message' => $e->getMessage()
    ]);

}

?>