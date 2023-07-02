<?php

namespace app\traits;

use app\database\Connection;

trait Create
{
    public function create(array $createFieldsAndValues)
    {
        $sql = sprintf("insert into %s (%s) values (%s)", $this->table, implode(', ', array_keys($createFieldsAndValues)), ':' . implode(', :', array_keys($createFieldsAndValues)));
        $prepared = Connection::connection()->prepare($sql);
        return $prepared->execute($createFieldsAndValues);        
    }
}