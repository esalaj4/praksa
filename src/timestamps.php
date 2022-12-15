<?php

namespace App\Src;

use Carbon\Carbon;

trait timestamps {
    public function update() 
    {
        $time = Carbon::now();
        $connection = Connection::getInstance();
        $table = $this->table;
        $sql = "UPDATE $table SET ".$this->array_to_pdo_params($this->attributes).", updated_at=? WHERE id=?";
        $queryData = array_values($this->attributes);
        $queryData[] = $time;
        $queryData[] = $this->id;
        $stmt = $connection->dbh->prepare($sql);
        $stmt->execute($queryData);
    }

    function array_to_pdo_params($array)
    {
        $temp = array();
        foreach (array_keys($array) as $name) {
          $temp[] = "$name = ?";
        }
        return implode(', ', $temp);
    }

    public function softDelete() 
    {
        $table = $this->table;
        $date = Carbon::now();
        $connection = Connection::getInstance();
        $values = array_values($this->attributes);
        $sql = "UPDATE $table SET deleted_at = ? WHERE id=?";
        $stmt = $connection->dbh->prepare($sql);
        $array = [$date,$this->id];
        $stmt->execute($array);  
    }
}