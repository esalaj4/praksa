<?php 
include('model.php');

class User extends Model{

    private $connection;
    public function __construct()
    {
        $this->table='users';
        $this->allowed=['name','surname'];
    } 
}

$user1 = new user();
$user2=new user();
$user3 = new user();
$user4=new user();
$user5=new user();

$user1->name="toni";
$user1->surname="saljj";
$user2->name="ena";
$user2->surname="salaj";
$user3->name="name3";
$user3->surname="surname3";
$user4->name="name4";
$user4->surname="surname4";
$user5->name="name5";
$user5->surname="surname5";

$user1->save();
/* echo '<pre>';
print_r(User::all());
echo '<pre>';  
 */
//$user3->delete();


