<?php

namespace app\traits;

use app\database\Connection;
use PDOException;

trait Read
{
    public function find($table, $fields = '*')
    {
        try {
            $query = Connection::connection()->query("select {$fields} from {$table}");
            return $query->fetchAll();
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
    }

    public function findBy($table, $field, $value, $fields = '*')
    {
        try {
            $prepare = Connection::connection()->prepare("select {$fields} from {$table} where {$field} = :{$field}");
            $prepare->execute([
                $field => $value
            ]);

            return $prepare->fetch();
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
    }
}
