<?php

require_once './vendor/autoload.php';
require_once "utils.php";

$token          = $_COOKIE['token'] ?? "No Token";
$id             = validate_token($token);

if($id !== false) {

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

            <button class="register-btn">Register</button>

            <h1>Pastel Paradise Contact Manager</h1>
            <p>Safely and securly store your contact infomation in the clould.</p>

            <form id="login-form">
                
                <div>
                    <label for="username">Username*</label>
                    <input type="text" name="password" placeholder="Username" id="login-form-username">
                </div>

                <div>
                    <label for="password">Password*</label>
                    <input type="password" name="password" placeholder="Password" id="login-form-password">
                </div>

                <button id="login-form-btn" type="submit">
                    Login In
                </button>

                <div class="form-error-msg">
                    
                </div>

                <a href="/forgot-password">Forgot Password</a>

            </form>

        </div>
    </div>

    <script src="scripts.js"></script>

</body>
</html>