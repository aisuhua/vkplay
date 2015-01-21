<?php

use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger\Adapter\File as LoggerAdapter;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Plugin\DbProfiler as DbProfilerPlugin;
use Phalcon\Db\Profiler;

$di = require APPLICATION_PATH . '/config/production/services.php';

$di->setShared('db', function () use ($config) {
    $eventsManager = new EventsManager();
    $logger = new LoggerAdapter($config->logs->db);

    $connection = new DbAdapter([
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset' => 'utf8',
    ]);

    $eventsManager->attach('db', new DbProfilerPlugin($logger, new Profiler(), $config->logs->default->level));

    $connection->setEventsManager($eventsManager);

    return $connection;
});

/** @var \Phalcon\Mvc\Router $router */
$router = $di->getShared('router');

$router
    ->add(
        '/test/(\w+)/(.+)',
        [
            'controller' => 'test',
            'action' => 'search',
            'finder' => 1,
            'keyword' => 2,
        ]
    )
    ->via('GET')
    ->setName('test');

$router
    ->add(
        '/test-reviews/(\w+)',
        [
            'controller' => 'test',
            'action' => 'reviews',
            'finder' => 1,
        ]
    )
    ->via('GET')
    ->setName('test-reviews');

return $di;