<?php

namespace Plugin;

use Phalcon\Db\Profiler;
use Phalcon\Events\Event;
use Phalcon\Logger\AdapterInterface as LoggerAdapterInterface;
use Phalcon\Db\AdapterInterface as DbAdapterInterface;
use Phalcon\Logger;
use Phalcon\Mvc\User\Plugin;

class DbProfiler extends Plugin
{

    /**
     * @var Profiler
     */
    protected $_profiler;

    /**
     * @var LoggerAdapterInterface
     */
    protected $_logger;

    /**
     * @var int
     */
    protected $_priority = Logger::DEBUG;

    /**
     * @var float
     */
    private $_previousTotalExecutionTime = 0;

    /**
     * @param LoggerAdapterInterface $_logger
     * @param Profiler $_profiler
     * @param $priority
     */
    public function __construct(LoggerAdapterInterface $_logger, Profiler $_profiler, $priority = Logger::DEBUG)
    {
        $this->_logger = $_logger;
        $this->_profiler = $_profiler;
        $this->_priority = $priority;
        
        $this->_logger->begin();
    }

    /**
     * @param Event $event
     * @param DbAdapterInterface $connection
     */
    public function beforeQuery(Event $event, DbAdapterInterface $connection)
    {
            $this->_profiler->startProfile(
                $connection->getSQLStatement(),
                $connection->getSQLVariables(),
                $connection->getSQLBindTypes()
            );
    }

    /**
     * @param Event $event
     * @param DbAdapterInterface $connection
     */
    public function afterQuery(Event $event, DbAdapterInterface $connection)
    {
        $this->_profiler->stopProfile();
        $sqlVariables = $connection->getSQLVariables() ?: [];

        foreach ($sqlVariables as $key => $value) {
            if ($key[0] !== ':') {
                unset($sqlVariables[$key]);
                $key = ':' . $key;
            }
            $sqlVariables[$key] = !is_array($value)
                ? $connection->escapeString((string) $value) // important
                : array_map(function ($v) use ($connection) {
                    return $connection->escapeString((string) $v); // important
                }, $value);
        }

        $statement = strtr($connection->getRealSQLStatement(), $sqlVariables);
        $time = $this->_profiler->getTotalElapsedSeconds() - $this->_previousTotalExecutionTime;
        $this->_previousTotalExecutionTime = $this->_profiler->getTotalElapsedSeconds();

        $this->_logger->log(
            sprintf('%s [Execution time: %.4f sec.]', $statement, round($time, 4)),
            $this->_priority
        );
    }

    /**
     * @return Profiler
     */
    public function getProfiler()
    {
        return $this->_profiler;
    }

    public function __destruct()
    {
        if ($this->_logger && $this->_profiler) {
            $this->_logger->log(
                sprintf(
                    'Total SQL execution time (%d queries): %.4f sec.',
                    $this->_profiler->getNumberTotalStatements(),
                    round($this->_profiler->getTotalElapsedSeconds(), 4)
                ),
                $this->_priority
            );
            
            $this->_logger->commit();
        }
    }

} 
