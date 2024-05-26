<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once './vendor/autoload.php';
require_once './middleware/validate_token.php';
require_once './middleware/get_user_from_token.php';

// Get token from cookies
if(!isset($_COOKIE['token'])) {
    header('Location:index.html');
    exit();
}

$token = $_COOKIE['token'];

// Verify the token
if(validate_token($token) === false) {

    header('Location:index.html');
    exit();
}

// Get info from token
$user = json_decode(get_user_from_token($token));



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <p><?php echo $user->email ?></p>
    <p><?php echo $user->id ?></p>

</body>
</html>