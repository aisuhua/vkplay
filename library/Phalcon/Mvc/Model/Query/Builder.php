<?php

namespace Library\Phalcon\Mvc\Model\Query;

use Library\Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Query\Builder as PhalconQueryBuilder;

class Builder extends PhalconQueryBuilder
{

    /**
     * @return Query
     */
    public function getQuery()
    {
        $query = new Query($this->getPhql(), $this->getDI());
        if (!empty($this->_bindParams)) {
            $query->setBindParams($this->_bindParams);
        }
        if (!empty($this->_bindTypes)) {
            $query->setBindTypes($this->_bindTypes);
        }
        return $query;
    }

}