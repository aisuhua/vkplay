<?php

$router = new Phalcon\Mvc\Router();
$router->setDefaults([
    'namespace' => 'Controller',
    'controller' => 'index',
    'action' => 'index',
]);

$router
    ->add('/', [
        'controller' => 'index',
        'action' => 'index',
    ])
    ->via(['POST', 'GET'])
    ->setName('homepage');

return $router;