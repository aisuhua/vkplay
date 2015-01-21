<?php

namespace Library\Ras;

use Phalcon\Logger\AdapterInterface;
use Phalcon\Logger\Formatter\Json;

class Logger
{

    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @var AdapterInterface
     */
    protected $_adapter;

    /**
     * @var string
     */
    protected $_logId;

    /**
     * @var array
     */
    protected $_filterFields;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->_adapter = $adapter;
        $this->_adapter->setFormatter(new Json());
    }

    /**
     * @param string|array $key
     * @param null|mixed $data
     * @param bool $merge
     * @return $this
     */
    public function log($key, $data = null, $merge = false)
    {
        if (is_string($key)) {
            $this->_log($key, $data, $merge);
        } elseif (is_array($key)) {
            foreach ($key as $k => $row) {
                $this->_log($k, $row, $merge);
            }
        }
        return $this;
    }

    /**
     * @param string|\Exception $e
     * @return $this
     */
    public function exception($e)
    {
        if (is_string($e)) {
            $e = new \Exception($e);
        }
        if ($e instanceof \Exception) {
            $this->log('common_exceptions', [$this->_format($e)], true);
        }
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @param bool $merge
     * @return $this
     */
    protected function _log($key, $data, $merge = false)
    {
        if (is_string($key)) {
            if (array_key_exists($key, $this->_data) && is_array($data) && $merge) {
                foreach ($data as &$row) {
                    $row = $this->_format($row);
                }
                $this->_data[$key] = array_merge($this->_data[$key], $data);
            } else {
                $data = $this->_format($data);
                $this->_data[$key] = $data;
            }
        }
        return $this;
    }

    protected function _format($data)
    {
        if (is_object($data)) {
            if ($data instanceof \stdClass) {
                $data = (array)$data;
            } elseif ($data instanceof \Exception) {
                $data = sprintf('%s in %s (%d)', $data->getMessage(), $data->getFile(), $data->getLine());
            } elseif (method_exists($data, 'toArray')) {
                $data = $data->toArray();
            } elseif (method_exists($data, '__toString')) {
                $data = $data->__toString();
            } else {
                $data = 'Object ' . get_class($data);
            }
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getLogId()
    {
        return $this->_logId;
    }

    /**
     * @param string $logId
     *
     * @return Logger
     */
    public function setLogId($logId)
    {
        $this->_logId = $logId;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilterFields()
    {
        return $this->_filterFields;
    }

    /**
     * @param array $filterFields
     * @return Logger
     */
    public function setFilterFields($filterFields)
    {
        $this->_filterFields = $filterFields;
        return $this;
    }

    public function __destruct()
    {
        if (!is_array($this->_data)) {
            $this->_data = [];
        }
        if (!empty($this->_filterFields) && is_array($this->_filterFields)) {
            $this->_data = array_intersect_key($this->_data, array_flip($this->_filterFields));
        }
        ksort($this->_data);
        $now = new \DateTime();
        $this->_data = array_merge([
            '_id' => $this->getLogId() ? : uniqid('', true),
            '_timestamp' => $now->format('c'),
            '_date' => $now->format('Y-m-d H:i:s'),
        ], $this->_data);
        $this->_adapter->log($this->_data, $this->_adapter->getLogLevel());
    }

}