<?php

class Connection
{
    private $host = "localhost";
    private $database = "enas";
    private $username = "ena";
    private $password = "password";

    public function connect()
    { 
    try {
        $conn = new PDO("mysql:host=$this->host;dbname=$this->database",$this->username,$this->password);
        echo "connected";
    } catch(PDOException $pe) {
        die("Couldn't connect to db" . $pe->getMessage());
    }
}
}
?>