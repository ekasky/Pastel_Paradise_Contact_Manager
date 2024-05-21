<?php

// Ensure the request coming in is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'POST request only']);
    exit();
}

// Make a connection to the db
$conn = new mysqli('mysql', 'user', 'password', 'contacts');

if ($conn->connect_error) {
    echo json_encode(['error' => 'Could not connect to db']);
    die();
}

// Extract the data from the request body
$body = json_decode(file_get_contents('php://input'), true);

// Check if all required fields are provided and not empty
$required_fields = ['first_name', 'last_name', 'username', 'password'];

foreach ($required_fields as $field) {
    if (!isset($body[$field]) || empty($body[$field])) {
        echo json_encode(['error' => "Missing required field: $field"]);
        die();
    }
}

$first_name = $body['first_name'];
$last_name = $body['last_name'];
$username = $body['username'];
$password = password_hash($body['password'], PASSWORD_BCRYPT);

// Query database to see if user already registered
$query = 'SELECT * FROM users WHERE username=?';

$statement = $conn->prepare($query);
$statement->bind_param("s", $username);
$statement->execute();

$result = $statement->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['error' => 'Username taken']);
    die();
}

// Add user to the database
$query = 'INSERT INTO users (first_name, last_name, username, password, created_at, last_login) VALUES (?, ?, ?, ?, ?, ?)';

$statement = $conn->prepare($query);
$statement->bind_param("ssssss", $first_name, $last_name, $username, $password, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
$success = $statement->execute();

if (!$success) {
    echo json_encode(['error' => 'Error while registering user']);
    die();
}

echo json_encode(['message' => 'User registered successfully']);

// Close statement and connection
$statement->close();
$conn->close();

?>
