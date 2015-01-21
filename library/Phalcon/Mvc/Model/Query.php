<?php

namespace Library\Phalcon\Mvc\Model;

use Phalcon\Mvc\Model\Query as PhalconQuery;

class Query extends PhalconQuery
{

    /**
     * @param array $bindParams
     * @param array $bindTypes
     * @return mixed
     */
    public function execute($bindParams = null, $bindTypes = null)
    {
        $params = array_merge($this->_bindParams ?: [], $bindParams ?: []);
        $params = array_filter($params, 'is_scalar');
        ksort($params);
        if (false !== $this->_cacheOptions) {
            $this->cache(array_replace_recursive([
                'key' => sha1($this->_phql . print_r($params, true)),
                'lifetime' => 7200,
            ], (array)$this->_cacheOptions));
        } else {
            $this->cache(null);
        }
        return parent::execute($bindParams, $bindTypes);
    }

} 