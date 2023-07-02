<?php

namespace app\database\models;

class User extends BaseModel
{
    protected $table = 'users';
    
    public function getTable()
    {
        return $this->table;
    }
    
}