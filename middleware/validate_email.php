<?php

function validate_email($email) {
    
    // Regular expression pattern for validating email addresses
    $pattern = '/^\S+@\S+\.\S+$/';
    
    // Check if the email matches the pattern
    if (preg_match($pattern, $email)) {

        return true;

    }

    return false;
}

?>