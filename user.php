<?php 
include('model.php');
class User extends Model{

    
    private $connection;
    public function __construct($connection)
    {
        parent::__construct($connection);
        $this->table='users';
        $this->allowed=['name','surname'];
        
    } 
}

$user2=new user($connection);
$user2->name="ena";
$user2->surname="salaj";

$user2->filterByProperty('surname');

?>