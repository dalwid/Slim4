<?php

namespace app\controllers;

use app\classes\Csrf;
use app\classes\Flash;
use app\classes\Validate;
use app\database\models\User as ModelsUser;
use Psr\Container\ContainerInterface;

class User extends Base
{
    private $user;
    private $validate;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->validate = new Validate;
        $this->user = new ModelsUser;
        $this->container = $container;
    }

    public function create($request, $response, $args)
    {
        $messages = Flash::getAll();

        $csrf = $this->container->get('csrf');

        $crossSiteRequestForgery = Csrf::csrf($request, $csrf);

        return $this->getTwig()->render($response, $this->setView('site/user_create'), [
            'title' => 'User Create',
            'messages' => $messages,
            'csrf' => $crossSiteRequestForgery
        ]);
    }

    public function edit($request, $response, $args)
    {
        $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

        $user = $this->user->findBy('users', 'id', $id);

        if (!$user) {
            Flash::set('message', 'usuário não existe', 'danger');
            return redirect($response, '/');
        }

        $messages = Flash::getAll();

        return $this->getTwig()->render($response, $this->setView('site/user_edit'), [
            'title' => 'User edit',
            'user' => $user,
            'messages' => $messages
        ]);
    }

    public function store($request, $response)
    {
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_DEFAULT);
        $lastName  = filter_input(INPUT_POST, 'lastName', FILTER_DEFAULT);
        $email     = filter_input(INPUT_POST, 'email', FILTER_DEFAULT);
        $password  = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);


        $this->validate->required(['firstName', 'lastName', 'email', 'password'])->exists($this->user, 'users', 'email', $email);
        $erros = $this->validate->getErros();

        if ($erros) {
            Flash::flashes($erros);
            return redirect($response, '/user/create');
        }

        $created = $this->user->create(['firstName' => $firstName, 'lastName' => $lastName, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);

        if ($created) {
            Flash::set('message', 'cadastrado com sucesso');
            return redirect($response, '/');
        }

        Flash::set('message', 'Ocorreu um erro ao cadastrar o usuario');
        return redirect($response, '/user/create');

        return $response;
    }

    public function update($request, $response, $args)
    {
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_DEFAULT);
        $lastName  = filter_input(INPUT_POST, 'lastName', FILTER_DEFAULT);
        $email     = filter_input(INPUT_POST, 'email', FILTER_DEFAULT);
        $password  = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);
        $id        = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

        $this->validate->required(['firstName', 'lastName', 'email', 'password']);
        $erros = $this->validate->getErros();

        if ($erros) {
            Flash::flashes($erros);
            return redirect($response, '/user/edit/' . $id);
        }

        $updated = $this->user->update(['fields' =>
        ['firstName' => $firstName, 'lastName' => $lastName, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)], 'where' => ['id' => $id]]);

        if ($updated) {
            Flash::set('message', 'Atualizado com sucesso');
            return redirect($response, '/user/edit/' . $id);
        }

        Flash::set('message', 'Ocorreu um erro ao atualizar', 'danger');
        return redirect($response, '/user/edit/' . $id);
    }

    public function destroy($request, $response, $args)
    {
        $id = filter_var($args['id'], FILTER_SANITIZE_NUMBER_INT);

        $user = $this->user->findBy('users', 'id', $id);

        if (!$user) {
            Flash::set('message', 'usuário não existe', 'danger');
            return redirect($response, '/');
        }

        $deleted = $this->user->delete('users', ['id' => $id]);

        if ($deleted) {
            Flash::set('message', 'Deletado com sucesso');
            return redirect($response, '/');
        }

        Flash::set('message', 'Ocorreu um erro ao deletar', 'danger');
        return redirect($response, '/');
    }
}
