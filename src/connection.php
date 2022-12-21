<?php

namespace App\Src;
use PDO;
use PDOException;
class Connection
{   public $dbh;
    private $host = "localhost";
    private $database = "praksa";
    private $username = "ena";
    private $password = "password";
    private static $instance = null;

    private function __construct()
    {    
        try {
            $this->dbh = new PDO("mysql:host=$this->host;dbname=$this->database",$this->username,$this->password);
        }
        catch (PDOException $pe) {
            die("Couldn't connect to db" . $pe->getMessage());
        }
    }

    public static function getInstance() 
    {
        if(self::$instance == null) {
            self::$instance = new Connection();
        }
        return self::$instance;
    }
}