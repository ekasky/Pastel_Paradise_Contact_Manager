<?php

// Make a connection to the database
$conn = new mysqli('localhost', 'apiuser', 'Password1', 'Contacts');

// Make sure connection was successful
if ($conn->connect_error) {
    echo json_encode(['error' => 'Could not connect to db']);
    die();
}

// Extract data from the request
$body = json_decode(file_get_contents('php://input'), true);

// Data for updating contact
$oldFN = $body['FirstName'];
$oldLN = $body['LastName'];
$oldPH = $body['Phone'];
$oldEM = $body['Email'];
$oldID = $body['UserID'];

$newFN = $body['newFirstName'];
$newLN = $body['newLastName'];
$newPH = $body['newPhone'];
$newEM = $body['newEmail'];
$newID = $body['newUserID'];

// Edit the contact
$sql = "Update Contacts SET FirstName=?, LastName=?, Phone=?, Email=?, UserID=? WHERE FirstName=? and LastName=? and Phone=? and Email=? and UserID=?";
$statement = $conn->prepare($sql);
$statement->bind_param("ssssssssss", $newFN, $newLN, $newPH, $newEM, $newID ,$oldFN, $oldLN, $oldPH, $oldEM, $oldID);
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
