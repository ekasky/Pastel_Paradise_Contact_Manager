<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './vendor/autoload.php';
require_once './middleware/validate_token.php';

/* Get headers */
$headers = apache_request_headers();

/* Check if the Authorization header is set */

if( isset($headers['Authorization']) ) {

    header('Location:index.html');
    exit();

}

/* Get token from header (This section got help from online src) */
if (preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
    $token = $matches[1];
} 
else {

    header('Location:index.html');
    exit();

}

/* Validate token */
if(validare_token($token) === false) {

    header('Location:index.html');
    exit();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Dashboard
</body>
</html>