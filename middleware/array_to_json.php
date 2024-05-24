<?php

function array_to_json($arr) {

    // Encode the array to JSON
    $json           = json_encode($arr);

    // Check to ensure the encoding was successful
    if($json === false) {

        error_log('[ERROR] Could not encode array to json');
        return null;

    }

    return $json;

}

?>