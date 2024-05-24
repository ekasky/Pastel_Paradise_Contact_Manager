<?php

function get_req_body() {

    // Read the request body
    $req_body           = file_get_contents('php://input');

    // Check to see if the request is empty
    if( empty($req_body) ) {

        error_log('[ERROR] Empty request body');
        return null;

    }

    // Convert body to json

    $json               = json_decode($req_body, true);

    // Check if JSON decoding was successful
    if($json === null && json_last_error() !== JSON_ERROR_NONE) {

        error_log('[ERROR] Invalid JSON format in request body');
        return null;

    }

    return $json;

}

?>