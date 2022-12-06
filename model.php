<?php
include("connection.php");
class Model {   
    protected array $attributes = [];
    protected array $allowed = [ ];
    public $table;
    private $connection;

    public function __construct()
    {
        
    }
    public function __set($name, $value) 
    {
        if (in_array($name,$this->allowed)) {
            $this->attributes[$name] = $value;
        } else { 
            throw new \Exception('Failed to assign attribute.');
        }
    }

    public function __get($name) 
    {
        if(array_key_exists($name, $this->attributes)){
            return $this->attributes[$name];
        }
        
        return null;
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    public function __call($name, $arguments)
    {
        $args = implode(', ', $arguments);
        throw new \Exception("Call to function '$name' with arguments '$args' failed\n");
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

    public function toArray()
    {
        return call_user_func('get_object_vars', $this);
    }

    public function update($id) 
    {
        $connection = Connection::getInstance();
        $table = $this->table;
        $sql = "UPDATE $table SET name = :name AND surname = :surname WHERE id = :id";
        $stmt= $connection->dbh->prepare($sql);
        $stmt->execute(['name' => $this->attributes['name'], 'surname' => $this->attributes['surname'], 'id' => $id]);

    }

    public function save() 
    {
        $connection = Connection::getInstance();
        $table = $this->table;
        $sql = "SELECT COUNT(*) AS num FROM $table WHERE name = :name AND surname = :surname";
        $stmt = $connection->dbh->prepare($sql);
        $stmt->bindValue(':name', $this->attributes['name']);
        $stmt->bindValue(':surname', $this->attributes['surname']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $name = $this->attributes['name'];
        $surname = $this->attributes['surname'];

        if($row['num'] > 0){
            $id = "SELECT id from $table WHERE name = $name AND surname = $surname";
            $stmt = $connection->dbh->prepare($id);
            $stmt->execute();
            echo ($stmt);
            self::update($id);
        } else{
            $stmt = $connection->dbh->prepare("INSERT INTO $table (name,surname) VALUES (:name, :surname)");
            $stmt->bindParam(':name',$this->attributes['name']);
            $stmt->bindParam(':surname',$this->attributes['surname']);
            $stmt->execute();
        }

        $stmt = null;
        $connection = null; 
    }

    public static function all()
    {
        try {
            $self = new static;
            $table = $self->table;
            $connection = Connection::getInstance();
            $stmt = $connection->dbh->prepare("SELECT name,surname FROM  $table");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
            $result = $stmt->fetchAll();
            return $result;
        } catch(PDOException $e){
            throw new \Exception("Error: '$e'");
        }
    }

    public static function filterById($id)
    {
        try { 
            $connection = Connection::getInstance();
            $self = new static;
            $table = $self->table;
            $query = "SELECT name,surname FROM $table WHERE id = :id";
            $stmt = $connection->dbh->prepare($query);
            $stmt->execute(['id' => $id]);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;       
        }
        catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function delete()
    {
        $connection = Connection::getInstance();
        $values = array_values($this->attributes);
        $name = $values[0];
        $surname = $values[1];
        $query = 'DELETE FROM users where name = :name AND surname = :surname ';
        $stmt = $connection->dbh->prepare($query);
        $stmt->execute(['name' => $name, 'surname' => $surname]);   
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

