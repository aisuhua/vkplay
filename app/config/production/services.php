<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Http\Response\Cookies;
use Library\Phalcon\Events\Manager as EventsManager;
use Plugin\Exception as ExceptionPlugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt;

$di = new FactoryDefault();

$di['config'] = $config;

$di['session'] = function () {
    $session = new SessionAdapter();
    $session->start();
    return $session;
};

$di->set('httpClient', '\Guzzle\Http\Client');

$di['modelsManager'] = '\Library\Phalcon\Mvc\Model\Manager';

$di->setShared('db', [
    'className' => '\Phalcon\Db\Adapter\Pdo\Mysql',
    'arguments' => [
        [
            'type' => 'parameter',
            'value' => $config->database->toArray(),
        ]
    ],
]);

$di->setShared('modelsMetadata', [
    'className' => $config->cache->metadata->className,
    'arguments' => [
        [
            'type' => 'parameter',
            'value' => $config->cache->metadata->options->toArray(),
        ],
    ],
]);

$di->setShared('modelsCacheFrontend', [
    'className' => $config->cache->models->frontend->className,
    'arguments' => [
        [
            'type' => 'parameter',
            'value' => $config->cache->models->frontend->options->toArray(),
        ],
    ],
]);

$di->setShared('servicesCacheFrontend', [
    'className' => $config->cache->services->frontend->className,
    'arguments' => [
        [
            'type' => 'parameter',
            'value' => $config->cache->services->frontend->options->toArray(),
        ],
    ],
]);

$di->setShared('viewCacheFrontend', [
    'className' => $config->cache->view->frontend->className,
    'arguments' => [
        [
            'type' => 'parameter',
            'value' => $config->cache->view->frontend->options->toArray(),
        ],
    ],
]);

$di->setShared('modelsCache', [
    'className' => $config->cache->models->backend->className,
    'arguments' => [
        [
            'type' => 'service',
            'name' => 'modelsCacheFrontend',
        ],
        [
            'type' => 'parameter',
            'value' => $config->cache->models->backend->options->toArray(),
        ],
    ],
]);

$di->setShared('servicesCache', [
    'className' => $config->cache->services->backend->className,
    'arguments' => [
        [
            'type' => 'service',
            'name' => 'servicesCacheFrontend',
        ],
        [
            'type' => 'parameter',
            'value' => $config->cache->services->backend->options->toArray(),
        ],
    ],
]);

$di->setShared('viewCache', [
    'className' => $config->cache->view->backend->className,
    'arguments' => [
        [
            'type' => 'service',
            'name' => 'viewCacheFrontend',
        ],
        [
            'type' => 'parameter',
            'value' => $config->cache->view->backend->options->toArray(),
        ],
    ],
]);

$di->setShared('eventsManager', '\Library\Phalcon\Events\Manager');

$di->setShared('dispatcher', function () use ($di) {
    /** @var EventsManager $evManager */
    $evManager = $di->getShared('eventsManager');
    $evManager->attach('dispatch', new ExceptionPlugin());
    $dispatcher = new Dispatcher();
    $dispatcher->setEventsManager($evManager);
    return $dispatcher;
});

$di->setShared('router', function () use ($config) {
    return require __DIR__ . '/../routes.php';
});

$di->setShared('url', function () use ($config, $di) {
    $url = new UrlResolver();
    $url->setBaseUri('/');
    return $url;
});

$di->setShared('voltService', function ($view, $di) use ($config) {
    $volt = new Volt($view, $di);

    $voltOptions = !empty($config->volt) ? $config->volt->toArray() : [];
    $volt->setOptions(array_merge([
        'compiledPath' => APPLICATION_PATH . '/cache/views/volt/',
        'compiledSeparator' => '-',
    ], $voltOptions));

    $compiler = $volt->getCompiler();
    $compiler
        ->addFilter('url_decode', 'urldecode')
        ->addFilter('rawurlencode', 'rawurlencode')
        ->addFilter('round', 'round')
        ->addFilter('count', 'count')
        ->addFunction('number_format', 'number_format')
        ->addFunction('array_slice', 'array_slice')
        ->addFunction('strpos', 'strpos');

    return $volt;
});

$di->setShared('view', function () use ($config, $di) {
    $view = new \Phalcon\Mvc\View();

    //WARNING: DO NOT TOUCH EVENTS PRIORITY!!!
    /** @var \Phalcon\Events\Manager $evManager */
    $evManager = $di->getShared('eventsManager');
    $evManager->enablePriorities(true);
    $evManager->attach('view', $di->get('contextPlugin'), 0);

    $view
        ->setViewsDir($config->application->viewsDir)
        ->setLayout('core')
        ->registerEngines(['.volt' => 'voltService'])
        ->setEventsManager($evManager);

    return $view;
});

$di->set('contextPlugin', [
    'className' => '\Plugin\Context',
    'calls' => [
        [
            'method' => 'setRequest',
            'arguments' => [
                ['type' => 'service', 'name' => 'request'],
            ]
        ],
        [
            'method' => 'setResponse',
            'arguments' => [
                ['type' => 'service', 'name' => 'response'],
            ]
        ],
    ],
]);

$di->setShared('cookies', function () {
    $cookies = new Cookies();
    $cookies->useEncryption(false);
    return $cookies;
});

return $di;