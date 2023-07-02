<?php

namespace app\controllers;

use app\classes\Flash;
use app\classes\Validate;
use app\database\models\User;
use app\traits\Read;
use Psr\Container\ContainerInterface;

class Home extends Base
{
    private $user;
    private $validate;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->user = new User;
        $this->validate = new Validate;
        $this->container = $container;
    }
    
    
    public function index($reques, $response)
    { 
        $messages = Flash::get('message');

        $cache = $this->container->get('cache');

        // $responseEteg = $cache->withEtag($response, md5(time()));
        // $response = $cache->withExpires($response, '+50 seconds');
        $response = $cache->withlastModified($response, '-50 seconds');

        return $this->getTwig()->render($response, $this->setView('site/home'), [
            'title'   => 'Home',
            'message' => $messages
        ]);         
    }
}