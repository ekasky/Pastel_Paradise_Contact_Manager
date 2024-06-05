<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'middleware.php';

header('Content-Type:application/json');

try {

    valid_request_type('POST');                                     // Ensure request is POST. Throws a 405 Method Not Allowed error if incorrect method
    $conn           = connect();                                    // Create a connection to the database. Throws a 500 Internal Server Error code if error occurs connecting to db.
    $session_record = valid_session($conn);                         // Checks if session is valid, if not it throws a 401 Unauthorized code
    $body           = get_request_body();                           // Get the request body. Throws a 400 Bad Request if error occurs when getting the request body.
    $user_id        = $session_record['user_id'];
    $search         = "%" . $body['search'] . "%";                  // Extracts the search field from the request body
    $contacts       = search($user_id, $search, $conn);

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