<?php

require_once './vendor/autoload.php';
require_once "utils.php";

$token          = $_COOKIE['token'] ?? "No Token";
$id             = validate_token($token);

if($id !== false) {

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header('location:dashboard.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="continer login-contaier">
        <div class="container-left">
            <p class="logo">Pastel Paradise</p>
        </div>

        <div class="container-right">

            <button class="register-btn" onclick="window.location.href='index.php'">Log In</button>

            <h1>Pastel Paradise Contact Manager</h1>
            <p>Safely and securly store your contact infomation in the clould.</p>

            <form id="register-form">

                <div>
                    <label for="first_name">First Name*</label>
                    <input type="text" name="first_name" placeholder="First Name" id="register-form-first_name">
                </div>

                <div>
                    <label for="last_name">Last Name*</label>
                    <input type="text" name="last_name" placeholder="Last Name" id="register-form-last_name">
                </div>
                
                <div>
                    <label for="username">Username*</label>
                    <input type="text" name="password" placeholder="Username" id="register-form-username">
                </div>

                <div>
                    <label for="password">Password*</label>
                    <input type="password" name="password" placeholder="Password" id="register-form-password">
                </div>

                <button id="register-form-btn" type="submit">
                    Register
                </button>

                <p class="form-error-msg" id="form-error-msg"></p>

            </form>

        </div>
    </div>

    <script src="js/register.js"></script>

</body>
</html>