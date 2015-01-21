<?php

namespace Plugin;

use Phalcon\Mvc\User\Plugin;

class Warning extends Plugin
{

    /**
     * @var array
     */
    protected $_warnings = [];

    public function __construct()
    {
        set_error_handler([$this, 'handle']);
    }

    /**
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     */
    public function handle($code, $message, $file, $line)
    {
        $this->_warnings[] = sprintf('<b>Warning (%s)</b>: %s, in <b>%s</b> on line <b>%d</b>', $code, $message, $file, $line);
    }

    public function __destruct()
    {
        echo join(nl2br(PHP_EOL), $this->_warnings);
        restore_error_handler();
    }

}