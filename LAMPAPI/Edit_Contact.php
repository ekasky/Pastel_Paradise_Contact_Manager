<?php

// Make a connection to the database
$conn = new mysqli('localhost', 'apiuser', 'Password1', 'COP4331');

// Make sure connection was successful
if ($conn->connect_error) {
    echo json_encode(['error' => 'Could not connect to db']);
    die();
}

// Extract data from the request
$body = json_decode(file_get_contents('php://input'), true);

// Data for updating contact
$ID = $body['userId'];

$olfFN = $body['FirstName'];
$oldLN = $body['LastName'];
$oldPH = $body['Phone'];
$oldEM = $body['Email'];

$newFN = $body['newFirstName'];
$newLN = $body['newLastName'];
$newPH = $body['newPhone'];
$newEM = $body['newEmail'];


// Edit the contact
$sql = "Update Contacts SET FirstName=?, LastName=?, Phone=?, Email=? WHERE FirstName=? and LastName=? and Phone=? and Email=? and UserID=?";
$statement = $conn->prepare($sql);
$statement->bind_param("sssssssss", $newFN, $newLN, $newPH, $newEM, $ID ,$oldFN, $oldLN, $oldPH, $oldEM);
$result = $statement->execute();

// Check if edit failed
if(!$result){
    echo json_encode(['error' => 'Error while editing the contact']);
    die();
}

echo json_encode(['message' => 'Contact edited successfully']);

// Close connection
$conn->close();

?>
