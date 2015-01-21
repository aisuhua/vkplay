<?php

namespace Library\Ras;

/**
 * Class Loggable
 * @package Library\Ras
 */
trait Loggable
{

    /**
     * @var Logger
     */
    protected $_logger;

    /**
     * @return Logger|\Phalcon\Logger\Adapter
     * @throws \RuntimeException
     */
    public function getLogger()
    {
        if ($this->_logger === null) {
            if (method_exists($this, 'getDI')) {
                $this->_logger = $this->getDI()->getShared('logger');
            } else {
                throw new \RuntimeException('Logger service cannot be injected because current class does not implement DiAwareInterface');
            }
        }
        return $this->_logger;
    }

    /**
     * @param Logger $logger
     * @return $this
     */
    public function setLogger(Logger $logger)
    {
        $this->_logger = $logger;
        return $this;
    }

} 