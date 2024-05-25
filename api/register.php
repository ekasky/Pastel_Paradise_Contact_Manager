<?php

require_once '../middleware/is_proper_req_type.php';
require_once '../middleware/get_req_body.php';
require_once '../middleware/check_required_fields.php';
require_once '../middleware/connect_to_db.php';
require_once '../middleware/find_user_by_email.php';
require_once '../middleware/check_password.php';
require_once '../middleware/validate_email.php';


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
if( ($missing = check_required_fields(['first_name', 'last_name', 'email', 'password'], $req_body)) !== null ) {

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

/* Check if email is taken */
if(($user = find_user_by_email($conn, $req_body['email'])) !== null) {
    
    http_response_code(409);

    echo json_encode([
        'error' => 'Email taken'
    ]);

    exit();

}

/* Ensure password strength is good */
if(check_password($req_body['password']) === false) {

    http_response_code(400);

    echo json_encode([
        'error' => 'Password too weak. Must contain one uppercase, one lowercase, one number, one special character, and be at least 8 characters long'
    ]);

    exit();

}

/* Ensure the email is in valid email form */
if(validate_email($req_body['email']) === false) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Invalid email'
    ]);

    exit();

}

/* Hash the password */
$hash = password_hash($req_body['password'], PASSWORD_BCRYPT);

/* Insert new user to the Users table */
$query          = 'INSERT INTO Users (first_name, last_name, email, password, last_login, created_at) VALUES (?,?,?,?,?,?)';
$statment       = $conn->prepare($query);
$date_time      = date('Y-m-d H:i:s');

$statment->bind_param('ssssss', $req_body['first_name'], $req_body['last_name'], $req_body['email'], $hash, $date_time, $date_time);
$result = $statment->execute();

if(!$result) {

    http_response_code(500);

    echo json_encode([
        'error' => 'Could not create user'
    ]);

    exit();

}

/* User creared successfully, redirect to login page */
header('Location:../index.html');
echo "Success";

?>