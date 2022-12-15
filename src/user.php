<?php 
namespace App\Src;

class User extends Model{

    private $connection;
    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        $this->table='users';
        $this->allowed=['name','surname'];
        self::$model = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
       
    } 
}
