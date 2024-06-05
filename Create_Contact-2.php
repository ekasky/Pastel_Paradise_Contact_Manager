<?php

// Connect to the database using MySQLi
$conn = new mysqli("localhost","apiuser", "Password1", "COP4331");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Get the request data
$inData = getRequestInfo();
if (is_null($inData)) {
    die(json_encode(["error" => "Invalid input data"]));
}

$FirstName = $inData["FirstName"];
$LastName = $inData["LastName"];
$Phone = $inData["Phone"];
$Email = $inData["Email"];
$UserID = $inData["UserID"];

// Prepare and execute the SQL statement using MySQLi
$stmt = $conn->prepare("INSERT INTO contacts (FirstName, LastName, Phone, Email, UserID) VALUES (?, ?, ?, ?, ?)");
if (!$stmt) {
    die(json_encode(["error" => "Prepare failed: " . $conn->error]));
}
$stmt->bind_param("sssss", $FirstName, $LastName, $Phone, $Email, $userId);

if (!$stmt->execute()) {
    die(json_encode(["error" => "Execute failed: " . $stmt->error]));
}

$stmt->close();
$conn->close();

returnWithError("");

// Functions
function getRequestInfo() {
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson($obj) {
    header('Content-type: application/json');
    echo $obj;
}

function returnWithError($err) {
    $retValue = json_encode(["error" => $err]);
    sendResultInfoAsJson($retValue);
}

?>