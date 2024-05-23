<?php

require_once "utils.php";

// Set the response headers to be JSON
header('Content-type: application/json');

// Ensure the incoming request is a GET request
if(!is_get_req()) exit();

// Validate the token
$token              = get_jwt();
$id                 = validate_token($token);

if($id === false) {

    http_response_code(401);

    echo json_encode([
        'error' => 'Invalid token'
    ]);

    exit();

}

// Connect to the database
$conn = connect_db();

if($conn === false) exit();

// Get all the contacts associated with the user id
$query              = 'SELECT * FROM Contacts WHERE user_id=?';
$statment           = $conn->prepare($query);

$statment->bind_param("i", $id);
$statment->execute();

$result             = $statment->get_result();

if($result->num_rows === 0) {

    http_response_code(404);

    echo json_encode([
        'error' => 'No Contacts Found' 
    ]);

    exit();

}

// Create a JSON array of all the records
$contacts = [];

while($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

// Return all the contacts
echo json_encode($contacts);

// Close db connection
$statment->close();
$conn->close();

?>