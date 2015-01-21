<?php

namespace Library\Phalcon\Mvc;

use Phalcon\Mvc\View as PhalconView;

/**
 * Class View
 * @package Library\Phalcon\Mvc
 */
class View extends PhalconView
{

    protected function _engineRender()
    {
        $manager = $this->getEventsManager();

        if ($manager) {
            $manager->fire('view:beforeEngineRender', $this);
        }

        $result = call_user_func_array('parent::_engineRender', func_get_args());

        if ($manager) {
            $manager->fire('view:afterEngineRender', $this);
        }

        return $result;
    }

}