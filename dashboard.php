<?php

require_once './vendor/autoload.php';
require_once "utils.php";

$token          = $_COOKIE['token'] ?? "No Token";
$id             = validate_token($token);

if($id === false) {

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('location:index.php');

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