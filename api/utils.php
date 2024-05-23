<?php

require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generate_token($user) {

    $payload = [
        'iss' => 'contactmanagerlamp.com',
        'iat' => time(),
        'exp' => time() + 3600,
        'id'  => $user['id']
    ];

    $key = getenv('SECRET_KEY');

    try {

        $jwt = JWT::encode($payload, $key, 'HS256');

        return $jwt;

    }
    catch(Exception $e) {

        return false;

    }

}

function validate_token($jwt) {

    try {

        $key            = getenv('SECRET_KEY');
        $decoded        = JWT::decode($jwt, new Key($key, 'HS256'));
        $id             = $decoded->id;

        return $id;

    } catch (Exception $e) {

        return false;
        
    }

}

function get_jwt() {

    $headers = apache_request_headers();

    if(!isset($headers['Authorization'])) {
    
        echo json_encode([
            'error' => "No user logged in"
        ]);
    
        exit();
    
    }

    $jwt = $headers['Authorization'];
    $jwt = str_replace('Bearer ', '', $jwt);

    return $jwt;

}

function connect_db() {

    $db_host            = getenv('DB_HOST');
    $db_user            = getenv('DB_USER');
    $db_pass            = getenv('DB_PASS');
    $db_name            = getenv('DB_NAME');

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check if the connection was sucessfull
    if($conn->connect_error) {

        http_response_code(500);

        echo json_encode([
            'error' => 'Could not connect to db'
        ]);

        return false;

    }

    return $conn;

}

function check_required_fields($required_fields, $supplied_fields) {

    $missing_fields = [];

    // Loop through all the required fields and track if any are missing
    foreach($required_fields as $field ) {

        if( !isset($supplied_fields[$field]) || empty($supplied_fields[$field]) ) {

            $missing_fields[] = $field;

        }

    }

    // If the length of the missing_fields array is not 0 one of the required fields was not supplied
    if(count($missing_fields) > 0) {
        return $missing_fields;
    }

    return true;

}

function is_post_req() {

    if($_SERVER['REQUEST_METHOD'] !== 'POST') {

        http_response_code(400);        // Bad Request
    
        echo json_encode([
            'error' => 'POST request only'
        ]);
    
        return false;
    
    }

    return true;

}

function get_request_body() {

    $respose_body       = file_get_contents('php://input');
    $body_json          = json_decode($respose_body, true);

    return $body_json;

}

function find_user_by_username($username, $conn) {

    $query                  = 'SELECT * FROM Users WHERE username=?';

    $statment               = $conn->prepare($query);
    
    $statment->bind_param("s", $username);
    $statment->execute();

    $result                 = $statment->get_result();

    $statment->close();


    if($result->num_rows > 0) {
        return $result->fetch_assoc();                  // Return the user record if found
    }

    return false;

}

?>