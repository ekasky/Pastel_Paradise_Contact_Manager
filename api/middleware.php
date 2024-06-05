<?php

function connect() {

    // Get the MySQL credentials from the env variables
    $DB_HOST            = getenv('DB_HOST_TWO');
    $DB_USER            = getenv('DB_USER_TWO');
    $DB_PASS            = getenv('DB_PASS_TWO');
    $DB_NAME            = getenv('DB_NAME_TWO');

    // Create a MySQL connection using the MySQLi library
    $conn               = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

    // Check if connection was successful
    if($conn->connect_error) {

        // Internal Server Error
        throw new Exception('Could not connected to database: ' . $conn->connect_error, 500);

    }

    // Return the connection variable if successful

    return $conn;

}

function valid_request_type($expected) {

    if($_SERVER['REQUEST_METHOD'] !== $expected) {

        // Method not allowed
        throw new Exception('Invalid request type.', 405);

    }

    return true;

}

function get_request_body() {

    // Get the request body
    $response           = file_get_contents('php://input');
    $res_json           = json_decode($response, true);

    // Check if the response json was decoded successfully
    if($res_json === null && json_last_error() !== JSON_ERROR_NONE) {

        // Bad request error code
        throw new Exception('Error decoding JSON: ' . json_last_error_msg(), 400);

    }

    // Return the response body as json
    return $res_json;

}

function required_request_fields($required_fields, $req_body) {

    // Array to hold missing fields
    $missing = [];

    // Loop through the reuqired fields and check if it is present in the request body
    foreach($required_fields as $field) {

        if(!isset($req_body[$field]) || empty($req_body[$field])) {

            $missing[] = $field;

        }

    }

    // If the missing array is not empty throw Exception
    if(count($missing) !== 0) {

        throw new Exception('Missing fields: ' . implode(', ', $missing), 400);

    }

    return null;

}

function valid_email($email) {

    $email_pattern = '/(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/i';

    if(!preg_match($email_pattern, $email)) {

        throw new Exception('Invalid email address', 400);
    
    }

    return true;

}

function valid_password($password) {

    $password_pattern = '"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"';

    if(!preg_match($password_pattern, $password)) {

        throw new Exception('Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one digit, and one special character (@$!%*?&)', 400);
    
    }

    return true;

}

function valid_phone($phone) {

    $phone_pattern = '/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/';

    if(!preg_match($phone_pattern, $phone)) {

        throw new Exception('Invalid phone number format', 400);
    
    }

    return true;

}

function passwords_match($password, $confirm_password) {

    if($password !== $confirm_password) {
        throw new Exception('Passwords do not match', 400);
    }

    return true;

}

function verfiy_password($password, $password_record) {

    if(!password_verify($password, $password_record)) {

        throw new Exception('Invalid login credentials', 401);      // If no user if found with the email, return a 401 code Unauthorized

    }

    return true;

}

function find_user_by_email($email, $conn) {

    $query = 'SELECT * FROM Users WHERE email=?';                                       // query to find the record by email in the Users table
    $stmt  = $conn->prepare($query);                                                    // prepare the query
    
    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);         // If there's an error preparing the query, throw a 500 Internal Server Error code
    }

    $stmt->bind_param('s', $email);                                                     // Inserts the email into the query
    $stmt->execute();                                                                   // Execute the SQL query

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);         // If there's an error executing the query, throw a 500 Internal Server Error code
    }

    $result = $stmt->get_result();                                                      // Get the results from the query

    $stmt->close();

    if($result->num_rows === 0) {
        return null;                                                                    // User not found by email, return null
    }

    return $result->fetch_assoc();                                                      // User record found, return the record

}

function insert_into_users($first_name, $last_name, $email, $hash, $conn) {

    $query = 'INSERT INTO Users (first_name, last_name, email, password, last_login, created_at) VALUES (?,?,?,?,NOW(),NOW())';       // Query into inset a new user to the database
    $stmt  = $conn->prepare($query);                                                    // prepare the query

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);         // If there's an error preparing the query, throw a 500 Internal Server Error code
    }

    $stmt->bind_param('ssss', $first_name, $last_name, $email, $hash);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);         // If there's an error executing the query, throw a 500 Internal Server Error code
    }

    if($stmt->affected_rows === 0) {
        throw new Exception('Error while creating a new user', 500);                    // If there was a error while inserting the user throw a 500 Internal Server Error
    }

    $stmt->close();

    return true;

}

function insert_into_contacts($first_name, $last_name, $email, $phone, $user_id, $conn) {

    $query = 'INSERT INTO Contacts (first_name, last_name, email, phone, user_id) VALUES (?,?,?,?,?)';       // Query into inset a new user to the database
    $stmt  = $conn->prepare($query);                                                    // prepare the query

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);         // If there's an error preparing the query, throw a 500 Internal Server Error code
    }

    $stmt->bind_param('ssssi', $first_name, $last_name, $email, $phone, $user_id);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);         // If there's an error executing the query, throw a 500 Internal Server Error code
    }

    if($stmt->affected_rows === 0) {
        throw new Exception('Error while creating a new contact', 500);                    // If there was a error while inserting the user throw a 500 Internal Server Error
    }

    $stmt->close();

    return true;

}

function update_contact($first_name, $last_name, $email, $phone, $user_id, $contact_id, $conn) {

    $query = 'UPDATE Contacts SET first_name=?, last_name=?, phone=?, email=? WHERE user_id=? AND id=?';
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);         // If there's an error preparing the query, throw a 500 Internal Server Error code
    }

    $stmt->bind_param('ssssii', $first_name, $last_name, $phone, $email, $user_id, $contact_id);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);         // If there's an error executing the query, throw a 500 Internal Server Error code
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('Contact not found or no changes were made', 404);
    }

    $stmt->close();

    return true;

}

function delete_contact($user_id, $contact_id, $conn) {
    
    $query = 'DELETE FROM Contacts WHERE id = ? AND user_id = ?';
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);
    }

    $stmt->bind_param('ii', $contact_id, $user_id);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);
    }

    $affectedRows = $stmt->affected_rows;

    if($affectedRows === 0) {
        throw new Exception('Contact not deleted or the contact does not exist', 404);
    }

    $stmt->close();

    return true;

}

function generate_session_id() {

    $session_id         = bin2hex(random_bytes(64));

    return $session_id;

}

function create_session_cookie($ssid) {

    setcookie('ssid', $ssid, [
        'expires'  => time() + 3600,                     // Expires in an hour
        'path'     => '/',                               // root path of site
        'samesite' => 'Strict',                          // Same site policy is strict for security
        'secure'   => true,                              // Only send over https
        'httponly' => true                               // cookie is only accessible through http
    ]);

}

function create_user_session($ssid, $user_id, $conn) {

    $query              = 'INSERT INTO Sessions (session_id, user_id, expires) VALUES (?,?, DATE_ADD(NOW(), INTERVAL 1 HOUR))';
    $stmt               = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);         // If there's an error preparing the query, throw a 500 Internal Server Error code
    }

    $stmt->bind_param('si', $ssid, $user_id);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);         // If there's an error executing the query, throw a 500 Internal Server Error code
    }

    if($stmt->affected_rows === 0) {
        throw new Exception('Error while creating a new user session', 500);            // If there was a error while inserting the sessions throw a 500 Internal Server Error
    }

    $stmt->close();

    return true;

}

function valid_session($conn) {

    if(!isset($_COOKIE['ssid'])) {

        throw new Exception('Invalid user session', 401);

    }

    $query = 'SELECT * FROM Sessions WHERE session_id = ? AND expires > NOW()';
    $stmt  = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);
    }

    $stmt->bind_param('s', $_COOKIE['ssid']);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);
    }

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Invalid user session', 401);
    }

    $sessionRecord = $result->fetch_assoc();

    $stmt->close();

    return $sessionRecord;

}

function destroy_session($conn) {

    if(!isset($_COOKIE['ssid'])) {

        throw new Exception('No user session found', 404);

    }

    $query = 'DELETE FROM Sessions WHERE session_id = ?';
    $stmt  = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);
    }

    $stmt->bind_param('s', $_COOKIE['ssid']);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);
    }

    setcookie('ssid', '', time() - 3600, '/', '', true, true);

    return true;

}

function get_users_contacts($user_id, $conn) {

    $query = 'SELECT * FROM Contacts WHERE user_id = ?';
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);
    }

    $stmt->bind_param('i', $user_id);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);
    }

    $result = $stmt->get_result();

    $contacts = [];                     // Array to store contacts from db

    // Fetch each row from the result and add it to the contacts array
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }

    $stmt->close();

    if(count($contacts) === 0) {

        throw new Exception('No contacts found', 404);

    }

    return $contacts;

}

function search($user_id, $search, $conn) {

    $query = 'SELECT * FROM Contacts WHERE user_id=? AND (first_name LIKE ? OR last_name LIKE ? OR phone LIKE ? OR email LIKE ?)';
    $stmt  = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error, 500);
    }

    $stmt->bind_param('issss', $user_id, $search, $search, $search, $search);
    $stmt->execute();

    if ($stmt->errno) {
        throw new Exception('Error executing statement: ' . $stmt->error, 500);
    }

    $result = $stmt->get_result();

    $searchResults = [];

    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }

    $stmt->close();

    return $searchResults;

}

function get_user_from_session_id($record, $conn) {

    // Get the user id from the record
    $user_id = $record['user_id'];

    // Find the Users record in the users table
    $query    = 'SELECT * FROM Users WHERE id=?';
    $statment = $conn->prepare($query);

     // Throw exception if error preparing the query
     if(!$statment) {
        throw new Exception('Error preparing query: ' . $conn->error, 500);
    }
    
    $statment->bind_param("i", $user_id);

    $execute  = $statment->execute();

    // Throw exception if error executing statement
    if(!$execute) {
        throw new Exception('Error executing query: ' . $statment->error, 500);
    }

    // Get the result from the query
    $result = $statment->get_result();

    // Close the statment
    $statment->close();

    // Check if user record was found
    if($result->num_rows === 0) {

        throw new Exception('No user found with the id associated with that session', 404);

    }

    // Fetch the user data
    $user = $result->fetch_assoc();

    return $user;


}

?>
