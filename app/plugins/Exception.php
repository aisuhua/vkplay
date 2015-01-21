<?php
namespace Plugin;

use Library\Ras\Loggable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

class Exception extends Plugin
{

    use Loggable;

    public function beforeException(Event $event, Dispatcher $dispatcher, \Exception $e)
    {
        $this->getLogger()->exception($e);
        $this->response->setStatusCode($e->getCode() ?: 500, $e->getMessage() ?: 'Application error');
        $dispatcher->forward([
            'namespace' => 'Controller',
            'controller' => 'error',
            'action' => 'index',
            'params' => [
                0 => $e->getMessage(),
            ],
        ]);
        return false;
    }

}