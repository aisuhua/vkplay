<?php

namespace Library\Phalcon\Events;

use Phalcon\Events\Manager as EventsManager;

class Manager extends EventsManager{

    public function attach($eventType, $handler, $priority = null)
    {
        $eventType = is_array($eventType) ? $eventType : [$eventType];
        if (is_array($handler)) {
            $handler = function () use ($handler) {
                return call_user_func_array($handler, func_get_args());
            };
        }
        foreach ($eventType as $eType) {
            parent::attach($eType, $handler, $priority);
        }
    }

}