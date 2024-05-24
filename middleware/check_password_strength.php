<?php

function check_password_strength($password) {

    /* Define regex pattern to enforce password strength */
    // Atleast 8 characters long
    // Contains one uppercase letter
    // Contains one lowercase letter
    // Contains one digit
    // Contains one special character
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    /* See if supplied password matches the pattern */

    if(!preg_match($pattern, $password)) {
        return false;
    }
    
    return true;

}

?>