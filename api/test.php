<?php

// Set the response headers to be JSON
header('Content-type: application/json');

echo json_encode([
    'message' => 'test',
    'db_host' => getenv('DB_HOST'),
    'db_name' => getenv('DB_NAME')
]);

?>
