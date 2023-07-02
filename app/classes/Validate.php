<?php

namespace app\classes;

class Validate
{
    private array $erros = [];

    public function required(array $fields)
    {
        foreach ($fields as $field) {
            if(empty($_POST[$field])){
                $this->erros[$field] = 'O campo é o brigatório';
            }
        }

        return $this;
    }

    public function exists($model, $table, $field, $value)
    {
        $data = $model->findBy($table, $field, $value);

        if($data)
        {
            $this->erros[$field] = 'Esse email já está cadastrado no banco de dados';
        }
        return $this;
    }

    public function email($email)
    {
        $validated = filter_var($email, FILTER_SANITIZE_EMAIL);
        if(!$validated){
            $this->erros['email'] = 'Email inválido';
        }
    }

    public function getErros()
    {
        return $this->erros;
    }

}