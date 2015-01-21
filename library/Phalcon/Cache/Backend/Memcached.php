<?php

namespace Library\Phalcon\Cache\Backend;

use Memcached as MemcachedDriver;
use Phalcon\Cache\Backend;
use Phalcon\Cache\BackendInterface;
use Phalcon\Cache\FrontendInterface;

class Memcached extends Backend implements BackendInterface
{

    const DRIVER_PERSISTENCE_ID = 'phalcon-memcached-pool';

    const DEFAULT_HOST = '127.0.0.1';
    const DEFAULT_PORT = 11211;
    const DEFAULT_WEIGHT = 1;

    /**
     * @var MemcachedDriver
     */
    protected $_backend;

    /**
     * @param FrontendInterface $frontend
     * @param array $options
     */
    public function __construct($frontend, $options = array())
    {
        $this->_backend = new MemcachedDriver(self::DRIVER_PERSISTENCE_ID);
        if (!isset($options['servers'])) {
            $options['servers'] = array(
                array(
                    'host' => self::DEFAULT_HOST
                )
            );
        }

        foreach ($options['servers'] as $server) {
            if (!array_key_exists('port', $server)) {
                $server['port'] = self::DEFAULT_PORT;
            }
            if (!array_key_exists('weight', $server)) {
                $server['weight'] = self::DEFAULT_WEIGHT;
            }
            $this->_backend->addServer($server['host'], $server['port'], $server['weight']);
        }

        if (isset($options['client']) && is_array($options['client'])) {
            $this->_backend->setOptions($options['client']);
        }
        unset($options['servers'], $options['client']);
        parent::__construct($frontend, $options);
    }

    /**
     * Returns a cached content
     *
     * @param int|string $keyName
     * @param   int $lifetime
     * @return  mixed
     */
    public function get($keyName, $lifetime = null)
    {
        if ($keyName === null) {
            $keyName = $this->_lastKey;
        }
        $keyName = $this->_addPrefix($keyName);
        $data = $this->_backend->get($keyName);
        if ($data) {
            return is_numeric($data) ? $data : $this->getFrontend()->afterRetrieve($data);
        }
        return null;
    }

    /**
     * Stores cached content into the file backend and stops the frontend
     *
     * @param int|string $keyName
     * @param string $content
     * @param int $lifetime
     * @param boolean $stopBuffer
     */
    public function save($keyName = null, $content = null, $lifetime = null, $stopBuffer = null)
    {
        if ($keyName === null) {
            $keyName = $this->_lastKey;
        }
        $keyName = $this->_addPrefix($keyName);
        $frontend = $this->getFrontend();
        if ($content === null) {
            $content =  $frontend->getContent();
        }
        if (!is_numeric($content)) {
            $content = $frontend->beforeStore($content);
        }
        if ($lifetime === null) {
            $lifetime = ($this->_lastLifetime !== null) ? $this->_lastLifetime : $frontend->getLifetime();
        }
        $this->_backend->set($keyName, $content, $lifetime);
        if ($stopBuffer) {
            $frontend->stop();
        }
    }

    /**
     * Deletes a value from the cache by its key
     *
     * @param int|string $keyName
     * @return boolean
     */
    public function delete($keyName)
    {
        if ($keyName === null) {
            $keyName = $this->_lastKey;
        }
        $keyName = $this->_addPrefix($keyName);
        $this->_backend->delete($keyName);
    }

    /**
     * Query the existing cached keys
     *
     * @param string $prefix
     * @return array
     */
    public function queryKeys($prefix = null)
    {
        return array();
    }

    /**
     * Checks if cache exists and it hasn't expired
     *
     * @param  string $keyName
     * @param  int $lifetime
     * @return boolean
     */
    public function exists($keyName = null, $lifetime = null)
    {
        if ($keyName === null) {
            $keyName = $this->_lastKey;
        }
        $keyName = $this->_addPrefix($keyName);
        $data = $this->_backend->get($keyName);
        return $data !== false;
    }

    /**
     * Flushes the cache
     *
     * @return bool
     */
    public function flush()
    {
        return $this->_backend->flush();
    }

    /**
     * Adds a prefix to the key if set.
     *
     * @param $keyName
     * @return string
     */
    protected function _addPrefix($keyName)
    {
        $options = $this->getOptions();
        if (!isset($options['prefix'])) {
            return $keyName;
        }
        return $options['prefix'] . $keyName;
    }

}