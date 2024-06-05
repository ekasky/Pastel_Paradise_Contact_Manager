<?php
$inData = getRequestInfo();

$ID = $inData["ID"];
$FirstName = $inData["FirstName"];
$LastName = $inData["LastName"];
$Phone = $inData["Phone"];
$Email = $inData["Email"];
$userId = $inData["userId"];

$conn = new mysqli("localhost", "apiuser", "Password1", "COP4331");

if ($conn->connect_error) 
{
    returnWithError($conn->connect_error);
} 
else
{
    $stmt = $conn->prepare("INSERT into COP4431 (ID, FirstName, LastName, Phone, Email, UserID) VALUES(?,?,?,?,?,?)");
    $stmt->bind_param("issssi", $ID, $FirstName, $LastName, $Phone, $Email, $userId);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    returnWithError("");
}

function getRequestInfo()
{
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson($obj)
{
    header('Content-type: application/json');
    echo $obj;
}

function returnWithError($err)
{
    $retValue = '{"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}
?>
