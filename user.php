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

$user1->name="name1";
$user1->surname="surname1";
$user2->name="name2";
$user2->surname="surname2";
$user3->name="name3";
$user3->surname="surname3";
$user4->name="name4";
$user4->surname="surname4";
$users = [$user1, $user2, $user3, $user4];

foreach($users as $user)
{
    $user->save();
}

$allUsers = User::all();
echo '<pre>';
print_r($allUsers);
echo '<pre>';

$user = User::filterById(28);
$user->name = "updatedName";
$user->save(); //update
$user->delete();
