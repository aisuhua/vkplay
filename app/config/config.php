<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(__DIR__ . '/../../app'));
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'development');

defined('ENVIRONMENT_PRODUCTION') || define('ENVIRONMENT_PRODUCTION', 'production');
defined('ENVIRONMENT_STAGING') || define('ENVIRONMENT_STAGING', 'staging');
defined('ENVIRONMENT_TESTING') || define('ENVIRONMENT_TESTING', 'testing');
defined('ENVIRONMENT_DEVELOPMENT') || define('ENVIRONMENT_DEVELOPMENT', 'development');

// get environment config
$config = require_once sprintf('%s/config/%s/config.php', APPLICATION_PATH, APPLICATION_ENV);

return new \Phalcon\Config($config);
