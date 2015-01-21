<?php

namespace Library\Phalcon\Cache\Backend;

use Phalcon\Cache\Backend;
use Phalcon\Cache\BackendInterface;
use Phalcon\Cache\FrontendInterface;

/**
 * Class Dummy
 * @package Library\Phalcon\Cache\Backend
 */
class Dummy extends Backend implements BackendInterface
{

    /**
     * \Phalcon\Cache\Backend constructor
     *
     * @param FrontendInterface $frontend
     * @param array $options
     */
    public function __construct($frontend, $options = null)
    {
        $this->_frontend = $frontend;
    }

    /**
     * Starts a cache. The $keyname allows to identify the created fragment
     *
     * @param int|string $keyName
     * @param   float $lifetime
     * @return  mixed
     */
    public function start($keyName, $lifetime = null)
    {
        return null;
    }

    /**
     * Stops the frontend without store any cached content
     *
     * @param boolean $stopBuffer
     */
    public function stop($stopBuffer = null)
    {

    }

    /**
     * Returns front-end instance adapter related to the back-end
     *
     * @return mixed
     */
    public function getFrontend()
    {
        return $this->_frontend;
    }

    /**
     * Returns the backend options
     *
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * Checks whether the last cache is fresh or cached
     *
     * @return boolean
     */
    public function isFresh()
    {
        return true;
    }

    /**
     * Checks whether the cache has starting buffering or not
     *
     * @return boolean
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Sets the last key used in the cache
     *
     * @param string $lastKey
     */
    public function setLastKey($lastKey)
    {
    }

    /**
     * Gets the last key stored by the cache
     *
     * @return string
     */
    public function getLastKey()
    {
        return '';
    }

    /**
     * Returns a cached content
     *
     * @param int|string $keyName
     * @param   float $lifetime
     * @return  mixed
     */
    public function get($keyName, $lifetime = null)
    {
        return null;
    }

    /**
     * Stores cached content into the file backend and stops the frontend
     *
     * @param int|string $keyName
     * @param string $content
     * @param float $lifetime
     * @param boolean $stopBuffer
     */
    public function save($keyName = null, $content = null, $lifetime = null, $stopBuffer = null)
    {

    }

    /**
     * Deletes a value from the cache by its key
     *
     * @param int|string $keyName
     * @return boolean
     */
    public function delete($keyName)
    {
        return false;
    }

    /**
     * Query the existing cached keys
     *
     * @param string $prefix
     * @return array
     */
    public function queryKeys($prefix = null)
    {
        return [];
    }

    /**
     * Checks if cache exists and it hasn't expired
     *
     * @param  string $keyName
     * @param  float $lifetime
     * @return boolean
     */
    public function exists($keyName = null, $lifetime = null)
    {
        return false;
    }
}