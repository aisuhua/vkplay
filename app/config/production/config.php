<?php

use Phalcon\Logger as PhalconLogger;

return [
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'vkplay',
        'password' => 'BnRX1fvUHB1rRNQ',
        'dbname' => 'vkplay',
        'charset' => 'utf8',
    ],
    'volt' => [
        'compileAlways' => false,
    ],
    'logs' => [
        'default' => [
            'filepath' => sprintf(APPLICATION_PATH . '/../logs/%s.log', $date),
            'level' => PhalconLogger::INFO,
        ],
        'gzl' => sprintf(APPLICATION_PATH . '/../logs/%s_GZL.log', $date),
        'db' => sprintf(APPLICATION_PATH . '/../logs/%s_DB.log', $date),
        'ean' => sprintf(APPLICATION_PATH . '/../logs/%s_EAN.log', $date),
    ],
    'application' => [
        'viewsDir' => APPLICATION_PATH . '/views/',
        'cacheDir' => APPLICATION_PATH . '/cache/',
        'modelsDir' => APPLICATION_PATH . '/models/',
    ],
    'cache' => [
        'metadata' => [
            'className' => '\Phalcon\Mvc\Model\MetaData\Apc',
            'options' => [
                'prefix' => 'meta-data',
                'lifetime' => 86400,
            ],
        ],
        'models' => [
            'frontend' => [
                'className' => '\Phalcon\Cache\Frontend\Data',
                'options' => [
                    'lifetime' => 7200,
                ],
            ],
            'backend' => [
                'className' => '\Library\Phalcon\Cache\Backend\Memcache',
                'options' => [
                    'servers' => [
                        [
                            'host' => '127.0.0.1',
                            'port' => '11211',
                        ]
                    ]
                ],
            ],
        ],
        'services' => [
            'frontend' => [
                'className' => '\Phalcon\Cache\Frontend\Data',
                'options' => [
                    'lifetime' => 14400,
                ],
            ],
            'backend' => [
                'className' => '\Library\Phalcon\Cache\Backend\Memcache',
                'options' => [
                    'servers' => [
                        [
                            'host' => '127.0.0.1',
                            'port' => '11211',
                        ]
                    ]
                ],
            ],
        ],
        'view' => [
            'frontend' => [
                'className' => '\Phalcon\Cache\Frontend\Output',
                'options' => [
                    'lifetime' => 7200,
                ],
            ],
            'backend' => [
                'className' => '\Library\Phalcon\Cache\Backend\Memcache',
                'options' => [
                    'servers' => [
                        [
                            'host' => '127.0.0.1',
                            'port' => '11211',
                        ]
                    ]
                ],
            ],
        ],
        'vk' => [
            'id' => '4742595',
            'secret' => 'lqy8C6Yw5EWsGJX24gTO',
        ],
    ],
];
