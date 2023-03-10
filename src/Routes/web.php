<?php

use Acris\App\Controllers\DocumentController;
use Acris\App\Controllers\HomeController;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $router) {

    $router->get('/', [HomeController::class, 'index']);
    $router->post('/getinfo', [DocumentController::class, 'setInfo']);
    $router->get('/document', [DocumentController::class, 'index']);
    $router->get('/controlsheet',[DocumentController::class, 'ControlSheet']);
    $router->get('/transfersheet',[DocumentController::class, 'TransferSheet']);
    $router->get('/foldersheet',[DocumentController::class, 'FolderSheet']);
    $router->get('/boxsheet',[DocumentController::class, 'BoxSheet']);
});


$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];

        list($class, $method) = array($handler[0], $handler[1]);
        call_user_func_array(array(new $class, $method), [array_merge($_POST, $_GET, $_FILES, $routeInfo[2])]);
        // ... call $handler with $vars
        break;
}
