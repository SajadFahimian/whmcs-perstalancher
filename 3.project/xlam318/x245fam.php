<?php

require 'bootstrap.php';

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers');

use Src\Controller\Controller;



$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST') {
    $controller = new Controller(__DIR__, $SQL_STR, HOME_PAGE, ALLOWED_HOME_PAGES);
    $response = $controller->processRequest();
    header($response['status_code_header']);
    if ($response['body']) {
        echo $response['body'];
    }
} else {
    header('HTTP/1.1 303 See Other');
    header('Location: ../');
    exit;
}
