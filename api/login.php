<?php

require_once '../middleware/is_proper_req_type.php';
require_once '../middleware/get_req_body.php';
require_once '../middleware/check_required_fields.php';
require_once '../middleware/connect_to_db.php';
require_once '../middleware/find_user_by_email.php';
require_once '../middleware/check_password.php';
require_once '../middleware/generate_token.php';

/* Set the response to return JSON */
header('Content-Type:application/json');

/* Check to incoming request type */
if(is_proper_req_type('POST') === false) {

    http_response_code(400);
    
    echo json_encode([
        'error' => 'Invalid request type'
    ]);

    exit();

}

/* Get the request body */
$req_body = get_req_body();

/* Check required fields for register are supplied */
if( ($missing = check_required_fields(['email', 'password'], $req_body)) !== null ) {

    http_response_code(401);

    echo json_encode([
        'error' => $missing
    ]);

    exit();

}

/* Create a connection to the database */
if(($conn = connect_to_db()) === null) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Internal Server Error'
    ]);

    exit();

}

/* Find the user record and ensure password matches */
$user = find_user_by_email($conn, $req_body['email']);

if($user === null || !password_verify($req_body['password'], $user['password'])) {
    
    http_response_code(401);

    echo json_encode([
        'error' => 'User not found or invalid password'
    ]);

    exit();

}

/* Generate a user jwt */
$jwt = generate_token($user, 3600);

if($jwt === null) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Could not login user'
    ]);

    exit();

}

/* Return jwt as http token and direct to dashboard */

setcookie("token", $jwt, time() + 3600, "/");
echo json_encode([
    'message' => 'Login successful'
]);


?>