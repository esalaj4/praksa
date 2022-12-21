<?php
namespace App\Src\Controllers;
use App\Src\Model;
use App\Src\Response;
use App\Src\Router;
use App\Src\User;

class ModelController
{ 
    public function all()
    {
        $loader = new \Twig\Loader\FilesystemLoader('views');
        $twig = new \Twig\Environment($loader);
        $users = User::all();
        $attributes = [];
        foreach($users as $key => $value) {
            array_push($attributes, $value->attributes);
        }
        echo $twig->render('allUsers.twig', array(
            'users' => $attributes,
        ));
    }

    public static function resolveView($callback)
    {
        $loader = new \Twig\Loader\FilesystemLoader('views');
        $twig = new \Twig\Environment($loader);

        if($callback === false) {
            Response::setStatusCode(404);
            echo $twig->render('_404.twig', array(
                'context' => 'Not found'
            ));
        }

        if(is_string($callback)) {
            echo $twig->render($callback);
        }
    }

    public function home()
    {
        $loader = new \Twig\Loader\FilesystemLoader('views');
        $twig = new \Twig\Environment($loader);
        $user = User::filterById(21);
        $params = $user->attributes;
        echo $twig->render('hello.twig', array(
            'name' => $params['name'],
            'surname' => $params ['surname']
        ));
    }
}