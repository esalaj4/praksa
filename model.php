<?php
include("connection.php");
class Model {   
    protected array $attributes = [];
    protected array $allowed = [ ];
    protected $table;
    private $connection;

    public function __construct($connection)
    {
        $this->connection=$connection;
    }
    public function __set($name, $value) 
    {
        if(in_array($name,$this->allowed))
          {  echo "Allowed, Setting '$name' to '$value'\n"; 
            $this->attributes[$name] = $value;}
        else{
            echo "Not allowed";
        }     
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

    public function save(){
        $name='users';
        $sql = sprintf(
            'INSERT INTO users (%s) VALUES ("%s")',
            implode(',',array_keys($this->attributes)),
            implode('","',array_values($this->attributes))
        );
        $stmt = $this->connection->dbh->prepare($sql);
        $stmt->execute();

    }

    public function all()
    {
        $query = "SELECT * FROM users";
        $keys = array_keys($this->attributes);

        try { 
            $stmt = $this->connection->dbh->prepare($query);
            $stmt->execute();
            $r = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
           //print_r($result);
            foreach ($result as $row) 
            {
                echo "NAME " . $row[$keys[0]]. " - SURNAME: " . 
                $row[$keys[1]]. "<br>";
            }
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function filterById($id){
        $query = "SELECT * FROM users WHERE id=$id";
        $keys = array_keys($this->attributes);

        try { 
            $stmt = $this->connection->dbh->prepare($query);
            $stmt->execute();
            $r = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
           // print_r($result);
            foreach ($result as $row) 
            {
                echo "NAME " . $row[$keys[0]]. " - SURNAME: " . 
                $row[$keys[1]]. "<br>";
            }
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM users WHERE id='$id'";
        $stmt = $this->connection->dbh->prepare($query);
        $stmt->execute();
    }

    public function filterByProperty($property)
    {
        $query = "SELECT * FROM users";
        $keys = array_keys($this->attributes);

        try { 
            $stmt = $this->connection->dbh->prepare($query);
            $stmt->execute();
            $r = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            $filtered = array_column($result,$property);
            print_r($filtered);

        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
      
    }
}   

?>

