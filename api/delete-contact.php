<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once 'middleware.php';

header('Content-Type:application/json');

try {

    valid_request_type('DELETE');                                         // Ensure request is DELETE. Throws a 405 Method Not Allowed error if incorrect method
    $conn           = connect();                                          // Create a connection to the database. Throws a 500 Internal Server Error code if error occurs connecting to db.
    $session_record = valid_session($conn);                               // Checks if session is valid, if not it throws a 401 Unauthorized code
    
    // Extract ID from url
    $url        = parse_url($_SERVER['REQUEST_URI']);
    $path       = explode('/', $url['path']);
    $contact_id = end($path);

    if(!is_numeric($contact_id)) {
        throw new Exception('Invalid contact id', 400);
    }

    // Extract the session
    $user_id    = $session_record['user_id'];

    delete_contact($user_id, $contact_id, $conn);                           // If there is a db error while removing contact it Throws a 500 Internal Server Error, Or if the contact is not found or does not belong to the user it throws a 404 Not Found

    http_response_code(200);

    echo json_encode([
        'message' => 'Contact deleted successfully'
    ]);

}
catch(Exception $e) {

    http_response_code($e->getCode());

    echo json_encode([
        'message' => $e->getMessage()
    ]);

}

?>