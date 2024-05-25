<?php

function is_proper_req_type($expected) {

    if($_SERVER['REQUEST_METHOD'] === $expected) {
        return true;
    }

    return false;

}

?>