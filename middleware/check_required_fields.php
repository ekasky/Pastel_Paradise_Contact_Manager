<?php

function check_required_fields($required, $req_body) {

    // Array to store any fields that may be missing in the req body
    $missing = [];

    // Loop through all the required fields and ensure they are included in the request body
    foreach($required as $field) {

        if( !isset($req_body[$field]) || empty($req_body[$field]) ) {
            $missing[] = $field;
        }

    }

    // If the length of the missing field is not zero return missing array signaling a error
    if(count($missing) !== 0) {

        error_log('[ERROR] Missing required field in request body: ' . implode(',', $missing));
        return "Missing required fields: " . implode(',', $missing);

    }

    // Return null signaling all required fields were provided
    return null;

}

?>