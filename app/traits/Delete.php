<?php

namespace app\traits;

use app\database\Connection;
use PDOException;

trait Delete
{
    public function delete(string $table, array $fieldId)
    {
        try {
            $fields = array_keys($fieldId);

            $sql = "delete from {$table} where";
            $sql.=" {$fields[0]} = :{$fields[0]}";
        
            $prepare = Connection::connection()->prepare($sql);
            
            $prepare->execute($fieldId);
            return $prepare->rowCount();
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
    }
}