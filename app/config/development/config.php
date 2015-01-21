<?php

$configProduction = @include sprintf('%s/config/production/config.php', APPLICATION_PATH) ?: [];

$configDevelopment = [
    'database' => [
        'host' => '127.0.0.1',
    ],
    'logs' => [
        'default' => [
            'level' => \Phalcon\Logger::DEBUG,
        ],
    ],
    'volt' => [
        'compileAlways' => true,
    ],
    'widgetsVolt' => [
        'compileAlways' => true,
    ],
    'cache' => [
        'metadata' => [
            'className' => '\Phalcon\Mvc\Model\MetaData\Memory',
            'options' => [],
        ],
        'models' => [
            'lifetime' => 0,
            'backend' => [
                'className' => '\Library\Phalcon\Cache\Backend\Dummy',
                'options' => [],
            ],
        ],
        'finder' => [
            'lifetime' => 0,
            'backend' => [
                'className' => '\Library\Phalcon\Cache\Backend\Dummy',
                'options' => [],
            ],
        ],
        'services' => [
            'lifetime' => 0,
            'backend' => [
                'className' => '\Phalcon\Cache\Backend\Memory',
                'options' => [],
            ],
        ],
        'view' => [
            'lifetime' => 0,
            'backend' => [
                'className' => '\Phalcon\Cache\Backend\Memory',
                'options' => [],
            ],
        ],
    ],
];

$configLocal = @include sprintf('%s/config/development/config.local.php', APPLICATION_PATH) ?: [];

$configDevelopment = array_replace_recursive(
    $configProduction,
    $configDevelopment,
    $configLocal
);

/***/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

defined('PHALCONDEBUG') || define('PHALCONDEBUG', true);
defined('USE_WARNING_PLUGIN') || define('USE_WARNING_PLUGIN', true);

(new \Phalcon\Debug())->listen();

return $configDevelopment;
