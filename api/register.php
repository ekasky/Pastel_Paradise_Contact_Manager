<?php

// Set the response headers to be JSON
header('Content-type: application/json');

// Check the incoming request type
if($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'message' => 'Only POST request allowed'
    ]);

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

if( !isset($body_json['username']) || empty($body_json['username']) ) {
    $missing_fields[] = 'Username';
}

if( !isset($body_json['password']) || empty($body_json['password']) ) {
    $missing_fields[] = 'Password';
}

if(count($missing_fields) > 0) {

    echo json_encode([
        'error' => 'Missing fields: ' . implode(', ', $missing_fields)
    ]);

}

// Extract each field
$first_name = $body_json['first_name'];
$last_name = $body_json['last_name'];
$username = $body_json['username'];
$password = $body_json['password'];

// Connect to the db
$conn = new mysqli('localhost', 'api', 'Password1', 'contacts');

if($conn->connect_error) {

    echo json_encode([
        'error' => 'Username Taken'
    ]);

    exit();

}

// Ensure the username is not already in use
$query = 'SELECT * FROM Users WHERE username=?';

$statment = $conn->prepare($query);
$statment->bind_param("s", $username);
$statment->execute();

$result = $statment->get_result();

if($result->num_rows > 0) {

    echo json_encode([
        'error' => 'Username taken'
    ]);

    exit();
}

// Hash the password using bcrypt
$password = password_hash($password, PASSWORD_BCRYPT);

// Insert new user to the Users table
$current_date = date('Y-m-d H:i:s');
$query = 'INSERT INTO Users (first_name, last_name, username, password, created_at, last_login) VALUES (?,?,?,?,?,?)';

$statment->prepare($query);
$statment->bind_param("ssssss", $first_name, $last_name, $username, $password, $current_date, $current_date);
$user = $statment->execute();

if($user) {
    echo json_encode([
        'message' => 'User registered successfully'
    ]);
}
else {

    echo json_encode([
        'error' => 'Could not register user'
    ]);

}

// Close the db connection
$statment->close();
$conn->close();

?>