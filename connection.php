<?php

class Connection
{   public $dbh;
    private $host = "localhost";
    private $database = "praksa";
    private $username = "ena";
    private $password = "password";


    public function __construct() {
        
        $options = array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        
        try {
            $this->dbh =  new PDO("mysql:host=$this->host;dbname=$this->database",$this->username,$this->password);
            echo "connected";
        }
        catch (PDOException $pe) {
            die("Couldn't connect to db" . $pe->getMessage());
        }
    }
}

$connection = new connection();
?>
