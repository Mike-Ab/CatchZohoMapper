<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 12/12/2016
 * Time: 10:17 AM
 */

namespace CatchZohoMapper\Request;


trait ZohoQuery
{
    protected $opts = [];

    public function select($columns)
    {
        $this->method = 'searchRecords';

        if (!is_array($columns)) {

            if ($columns == '*' || strtolower($columns) == 'all') {
                return true;
            }

            $this->opts['selectColumns'] = explode(',', $columns);
        }
        else {
            $this->opts['selectColumns'] = $columns;
        }

        return $this;
    }

    public function from($recordType)
    {
        $this->recordType = $recordType;
        return $this;
    }

    public function offset($offset)
    {
        $this->opts['fromIndex'] = $offset;
        return $this;
    }

    public function limit($limit)
    {
        if(isset($this->opts['fromIndex'])){
            $this->opts['toIndex'] = intval($this->opts['fromIndex']) + $limit;
        }
        else {
            $this->opts['toIndex'] = $limit;
        }
        return $this;
    }

    public function where(array $criteria)
    {
        $this->opts['criteria'][] = $criteria;
        return $this;
    }

    public function orWhere(array $criteria)
    {
        $this->opts['criteria'][] = $criteria;
        return $this;
    }
}