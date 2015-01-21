<?php

return (new \Phalcon\Loader())->registerNamespaces([
    'Controller' => APPLICATION_PATH . '/controllers/',
    'Model' => APPLICATION_PATH . '/models/',
    'Service' => APPLICATION_PATH . '/services/',
    'Traits' => APPLICATION_PATH . '/traits/',
    'Form' => APPLICATION_PATH . '/forms/',
    'Plugin' => APPLICATION_PATH . '/plugins/',
    'Library' => APPLICATION_PATH . '/../library/',
    'Extension' => APPLICATION_PATH . '/extensions/',
    'Tag' => APPLICATION_PATH . '/tags/',
])->register();