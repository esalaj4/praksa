<?php
include("connection.php");
include("timestamps.php");
require 'vendor/autoload.php';

use Carbon\Carbon;
use Carbon\Traits\ToStringFormat;

class Model 
{
    use timestamps;
    protected array $attributes = [];
    protected array $allowed = [ ];
    private $id;
    protected $table;
    private $connection;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function __construct($allowed, $attributes, $table)
    {
        $this->allowed = $allowed;
        $this->attributes = $attributes;
        $this->table = $table;
    }

    protected function hydrate($data)
    {
        $model = new Model($this->allowed, $this->attributes, $this->table);
        $values = array_values($data); 
        $arr_length = count($values);
        $id = $data['id'];
        $br=0;

        foreach($this->allowed as $collumn)
        {
            if ($br < $arr_length - 1) {
                $model->$collumn = $values[$br]; 
                $br++; 
                $model->setId($id);
            } else {
                $br = 0;
            }
        }
        return $model;
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
        if(array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        return null;
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    public function _isIdSet()
    {
        return isset($this->id);
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
        return "Attributes: '$newAttributes'";
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

    public function save() 
    {
        $connection = Connection::getInstance();
        $table = $this->table;
        $columns = implode(',',$this->allowed);
        $values = array_values($this->attributes);
        $placeholders = implode(',', array_fill(1, count($this->allowed), '?'));

        if($this->_isIdSet() == true) {
            $this->update();
        } else {
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
            $stmt = $connection->dbh->prepare($sql);
            $stmt->execute($values);
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
            $values = implode(",",$self->allowed);
            $stmt = $connection->dbh->prepare("SELECT $values,id FROM  $table WHERE deleted_at IS NULL");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $users = [];
            $keys = array_keys($result);

            foreach($keys as $key) {
                $user = $self->hydrate($result[$key]);
                array_push($users, $user);
            }
            return $users;
        } catch (PDOException $e) {
            throw new \Exception("Error: '$e'");
        }
    }

    public static function filterById($id)
    {
        try { 
            $connection = Connection::getInstance();
            $self = new static();
            $table = $self->table;
            $values = implode(",",$self->allowed);
            $query = "SELECT $values,id FROM $table WHERE id = :id AND deleted_at IS NULL";
            $stmt = $connection->dbh->prepare($query);
            $stmt->execute(['id' => $id]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(empty($result)) {
                exit;
            } else {
                $user = $self->hydrate($result[0]);
                return $user;
            }             
        } catch (PDOException $e) {
            throw new \Exception("Error: '$e'");
        }
    }
    
    public static function filterByProperty($property)
    {
        $self = new static;
        $table = $self->table;
        $query = "SELECT * FROM $table";
        $connection = Connection::getInstance();

        try { 
            $stmt = $connection->dbh->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $filtered = array_column($result,$property);
            return $filtered;
        } catch (PDOException $e) {
            throw new \Exception("Error: '$e'");
        }  
    }   
}   
