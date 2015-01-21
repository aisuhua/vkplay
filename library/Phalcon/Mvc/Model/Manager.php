<?php

namespace Library\Phalcon\Mvc\Model;

use Library\Phalcon\Mvc\Model\Query\Builder;
use Phalcon\Mvc\Model\Manager as PhalconModelManager;

class Manager extends PhalconModelManager
{

    /**
     * @param null $params
     * @return Builder
     */
    public function createBuilder($params = null)
    {
        $builder = new Builder($params, $this->getDI());
        return $builder;
    }

    /**
     * @param string $phql
     * @return Query
     */
    public function createQuery($phql)
    {
        $query = new Query($phql, $this->getDI());
        return $query;
    }

    /**
     * @param string $phql
     * @param array $placeholders
     * @return mixed
     */
    public function executeQuery($phql, $placeholders = null)
    {
        $query = new Query($phql, $this->getDI());
        return $query->execute($placeholders);
    }

} 