<?php
include("connection.php");
class Model {
    private array $attributes = [];
    protected array $allowed = [];
    protected $table;
    public function __set($name, $value) 
    {
        echo "Setting '$name' to '$value'\n"; 
        $this->attributes[$name] = $value;
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
   
}   

$obj = new Model;
$obj->atr=2;

$obj->atr2=4;
$obj->runTest("blabla");
echo $obj;

var_dump($obj->toArray());


?>
