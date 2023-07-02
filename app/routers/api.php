<?php
use Slim\Routing\RouteCollectorProxy;

// require '../app/middlewares/logged.php';

$app->group('/api', function(RouteCollectorProxy $group){
    $group->get('/users', function($requeste, $response){
        
        $payload = json_encode(['name' => 'Avraham']);

        $response->getBody()->write($payload);
        
        return $response->withHeader('Content-type', 'application/json', 200);
    });


    $group->get('/data', function($requeste, $response){
        return $response;
    });
})->add($logged);