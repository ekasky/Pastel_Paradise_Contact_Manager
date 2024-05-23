<?php

require_once '../vendor/autoload.php';
require_once './util/validate_token.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Set the response headers to be JSON
header('Content-type: application/json');


// Get token from the header
$headers = apache_request_headers();

if(!isset($headers['Authorization'])) {
    
    echo json_encode([
        'error' => "No user logged in"
    ]);

    exit();

}

$jwt = $headers['Authorization'];
$jwt = str_replace('Bearer ', '', $jwt);


// Validate the token using the validateToken function
$id = validateToken($jwt);

if($id === false) {
    echo "Bad Token";
    exit();
}

// Get the data from the request body
$respose_body = file_get_contents('php://input');
$body_json = json_decode($respose_body, true);

// Ensure all the required fields were supplied in the request body
$missing_fields = [];

if( !isset($body_json['first_name']) || empty($body_json['first_name']) ) {
    $missing_fields[] = 'First Name';
}

if( !isset($body_json['last_name']) || empty($body_json['last_name']) ) {
    $missing_fields[] = 'Last Name';
}

if( !isset($body_json['phone_number']) || empty($body_json['phone_number']) ) {
    $missing_fields[] = 'Phone Number';
}

if( !isset($body_json['email']) || empty($body_json['email']) ) {
    $missing_fields[] = 'Email';
}

if(count($missing_fields) > 0) {

    echo json_encode([
        'error' => 'Missing fields: ' . implode(', ', $missing_fields)
    ]);

}

// Extract each field
$first_name = $body_json['first_name'];
$last_name = $body_json['last_name'];
$phone_number = $body_json['phone_number'];
$email = $body_json['email'];

// Connect to the db
$conn = new mysqli(getenv('DB_HOST'), getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'));

if($conn->connect_error) {

    echo json_encode([
        'error' => 'Could not connect to db'
    ]);

    exit();

}


// Insert the new contact to the db
$query = 'INSERT INTO Contacts (first_name, last_name, phone_number, email, user_id) VALUES (?,?,?,?,?)';

$statment = $conn->prepare($query);
$statment->bind_param("ssssi", $first_name, $last_name, $phone_number, $email, $id);
$result = $statment->execute();

if(!$result) {

    echo json_encode([
        'error' => 'Could not create new contact'
    ]);

    exit();

}

echo json_encode([
    'message' => 'Contact Created'
]);

// Close db conneciton
$statment->close();
$conn->close();

?>
