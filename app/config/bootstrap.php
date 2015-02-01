<?php

/** register composer auto-loader */
require __DIR__ . '/../../vendor/autoload.php';

/** Read the configuration */
$config = require __DIR__ . '/../../app/config/config.php';

/** Read auto-loader */
require 'loader.php';

/** Read services */
$di = require 'services.php';

return new \Phalcon\Mvc\Application($di);
