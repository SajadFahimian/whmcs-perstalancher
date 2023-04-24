<?php

require '../../xlam318/bootstrap.php';


header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Max-Age: 6300');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers');


use Src\Controller\Controller;





$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'GET') {

    $uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $uri = explode('/', $uri);

    if (!isset($_GET['payload'])) {
        header("HTTP/1.1 400 Bad Request");
        exit();
    }

    $controller = new Controller($_GET['payload']);
    $response = $controller->processRequest();

    header($response['status_code_header']);
    if (isset($response['body'])) {
        echo $response['body'];
    }

} else {
    header('HTTP/1.1 303 See Other');
    header('Location: ../');
    exit;
}
