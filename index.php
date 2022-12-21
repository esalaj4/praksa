<?php

use App\Src\Controllers\ModelController;
use App\Src;
use App\Src\Request;
use App\Src\Router;

require_once __DIR__ . './vendor/autoload.php';

$router = new Router(dirname(__DIR__));
$router->get('/Factory/', [ModelController::class,'home']);
$router->get('/Factory/all',[ModelController::class,'all']);
$request = new Request;
$router->resolve($request);
