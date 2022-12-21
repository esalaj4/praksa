<?php

namespace App\Src;

use App\Src\Controllers\ModelController;

class Router
{
    protected static array $routes = [];
    public static string $ROOT_DIR;
    public static function get(string $path, $callback) 
    {
        self::$routes['get'][$path] = $callback; 
    }
    public static function post(string $path, $callback) 
    {
        self::$routes['post'][$path] = $callback;
    }

    public function __construct($path)
    {
        self::$ROOT_DIR = $path;
    }

    public function resolve($request)
    {
        $path = Request::getPath(); 
        $method = Request::getMethod();
        $callback = self::$routes[$method][$path] ?? false;
        if(($callback === false) || (is_string($callback))) {
            ModelController::resolveView($callback);
            exit;
        }

        if(is_array($callback))  {
            $callback[0] = new $callback[0]();
        }
        echo call_user_func($callback); 
    }
}  