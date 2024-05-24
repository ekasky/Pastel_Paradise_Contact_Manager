<?php

function is_proper_req_type($expected) {

    return $_SERVER['REQUEST_METHOD'] === $expected;

}

?>