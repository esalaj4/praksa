<?php
include("connection.php");
class Model {

   
    private array $attributes = [];
    protected array $allowed = ['red','blue'];
    protected $table;
    private $connection;

    public function __construct($connection)
    {
        $this->connection=$connection;
    }
    public function __set($name, $value) 
    {
        echo "Setting '$name' to '$value'\n"; 
        $this->attributes[$name] = $value;
       /*  if((in_array($name,$this->allowed) || empty($this->allowed)) ){
            $query = "ALTER TABLE models ADD $name VARCHAR(50) AFTER id";
            $stmt = $this->connection->dbh->prepare($query);
            $stmt->execute();}   */
 
    }

    public function __get($name) 
    {
        echo "Getting '$name'\n";

        if(array_key_exists($name,$this->attributes)){
            return $this->attributes[$name];
        }
        
        return null;
    }

    public function __isset($name)
    {
        echo "Is '$name' set?\n";
        return isset($this->attributes[$name]);
    }

    public function __unset($name)
    {
        echo "Unsetting '$name'?\n";
        unset($this->data[$name]);
    }
    public function __call($name, $arguments)
    {
        echo "Calling object method '$name' "
             . implode(', ', $arguments). "\n";
    }

    public function __toString()
    {
        $newAttributes = implode(", ",$this->attributes);
        $newAllowed = implode(", ",$this->allowed);
        return "Dostupni atributi za model: '$newAttributes'";
    }

    public function __sleep()
    {
        $newAttributes = implode(", ",$this->attributes);
        return array($newAttributes);
    }

    public function toArray(){
        return call_user_func('get_object_vars', $this);
    }

    public function store(){
        var_dump($this);
        foreach($this->attributes as $key=>$key_value)
        {
            echo "Key=" .$key . "Value=" .$key_value;
            echo "<br>";
            $query = "INSERT INTO models($key) VALUES($key_value)";
            $stmt = $this->connection->dbh->prepare($query);
            $stmt->execute();
        }

        
    }

    public function all()
    {
        $query = "SELECT * FROM models";
        $stmt = $this->connection->dbh->prepare($query);
        $stmt->execute();
        $models = $stmt ->fetchAll();
        foreach($models as $model)
        {
            echo "ID:'$model[id]': '$model[attributes]' \n";
        }
    }

    public function filterById($id){
        $query = "SELECT * FROM models WHERE id='$id'";
        $stmt = $this->connection->dbh->prepare($query);
        $stmt->execute();
        $models = $stmt ->fetchAll();
        foreach($models as $model)
        {
            echo "ID:'$model[id]': '$model[attributes]' \n";
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM models WHERE id='$id'";
        $stmt = $this->connection->dbh->prepare($query);
        $stmt->execute();
    }

    public static function filterByProperty($property,$connection)
    {
        $query = "SELECT * FROM models WHERE attributes LIKE '%$property%'";
        $stmt = $connection->dbh->prepare($query);
        $stmt->execute();
        $models = $stmt ->fetchAll();
        foreach($models as $model)
        {
            echo "ID:'$model[id]': '$model[attributes]' \n";
        }
    }
}   
 
$obj = new Model($connection);
$obj->surname='salaj';
$obj->store();
//var_dump($obj);



?>
