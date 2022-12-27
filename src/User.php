<?php 
namespace App\Src;

class User extends Model{

    private $connection;
    public function __construct()
    {
        $this->table='users';
        $this->allowed=['name','surname'];
        self::$model = $this;
    } 
}
