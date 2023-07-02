<?php

namespace app\controllers;

use app\classes\Flash;
use app\classes\Login as ClassesLogin;
use app\classes\Validate;

class Login extends Base
{
    private $login;

    public function __construct()
    {
        $this->login = new ClassesLogin;
    }
    
    public function index($request, $response)
    {
        
        $messages = Flash::getAll();
        var_dump($messages);
        return $this->getTwig()->render($response, $this->setView('site/login'), [
            'title' => 'Home',
            'messages' => $messages
        ]);
    }

    public function store($querest, $response)
    {
        $email     = filter_input(INPUT_POST, 'email'    , FILTER_DEFAULT);
        $password  = filter_input(INPUT_POST, 'password' , FILTER_DEFAULT);

        $validate = new Validate;
        $validate->required(['email', 'password'])->email($email);
        $erros = $validate->getErros();

        if($erros){
            Flash::flashes($erros);
            return redirect($response, '/login');
        }

        $logged = $this->login->login($email, $password);

        if($logged){
            return redirect($response, '/');
        }
        Flash::set('message', 'Ocorreu um erro ao logar, tente novamente em segundos');
        return redirect($response, '/login');
    }

    public function destroy($querest, $response)
    {
        $this->login->logout();
        return redirect($response, '/');
    }
}