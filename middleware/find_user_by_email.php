<?php

function find_user_by_email($conn, $email) {

    $query      = 'SELECT * FROM Users WHERE email=?';
    $statment   = $conn->prepare($query);
    
    $statment->bind_param("s", $email);
    $statment->execute();

    $result     = $statment->get_result();

    if($result->num_rows !== 0) {

        error_log('[ERROR] User already registered');
        return $result->fetch_assoc();

    }

    return null;

}

?>