<?php

use App\Src\Controllers\ModelController;
use App\Src;

require_once __DIR__ . './vendor/autoload.php';

$model = new App\Src\User(dirname(__DIR__));

$model->router->get('/', [ModelController::class,'home']);

$model->router->get('/contact',[ModelController::class,'contact']);

//$model->router->post('/contact', [ModelController::class,'contact']);

$model->run(); 

