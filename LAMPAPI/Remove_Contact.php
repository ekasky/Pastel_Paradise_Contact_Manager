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

// Get id of contact to be deleted
$fn = $body['FirstName'];
$ln = $body['LastName'];
$ph = $body['Phone'];
$em = $body['Email'];
$id = $body['UserID'];

// Delete the contact
$sql = "DELETE FROM Contacts WHERE FirstName=? and LastName=? and Phone=? and Email=? and UserID=?";
$statement = $conn->prepare($sql);
$statement->bind_param("sssss", $fn, $ln, $ph, $em, $id);
$result = $statement->execute();

// Check if deletion failed
if(!$result){
    echo json_encode(['error' => 'Error while deleting the contact']);
    die();
}

echo json_encode(['message' => 'Contact deleted successfully']);

// Close connection
$conn->close();

?>


