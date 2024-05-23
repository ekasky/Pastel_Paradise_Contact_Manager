<?php

error_reporting(E_ALL); 
ini_set('display_errors', 1);

require_once './vendor/autoload.php';
require_once "utils.php";

$token          = $_COOKIE['token'] ?? "No Token";
$id             = validate_token($token);

if($id === false) {

    header('location:index.html');

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
    Contacts Dashboard
    <p><?php echo $id ?></p>
</body>
</html>