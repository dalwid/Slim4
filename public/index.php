<?php
session_start();
require '../vendor/autoload.php';

use DI\Container;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;

$container = new Container();
AppFactory::setContainer($container);

$container->set('cache', function () {
    return new \Slim\HttpCache\CacheProvider();
});

$app = AppFactory::create();

$container->set('csrf', function () use ($app) {
    $responseFactory = $app->getResponseFactory();
    return new Guard($responseFactory);
});

require '../app/routers/site.php';
require '../app/routers/user.php';

$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    $response->getBody()->write('Something wrong');
    return $response;
});

$app->run();