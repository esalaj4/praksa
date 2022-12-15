<?php
namespace App\Src\Controllers;
use App\Src\Model;
use App\Src\User;

class ModelController
{
    public function contact()
    {
        return Model::$model->router->renderView('contact');
    }

    public function home()
    {
        $user = User::filterById(21);
        $params = $user->attributes;
        return Model::$model->router->renderView('home',$params);
    }
}