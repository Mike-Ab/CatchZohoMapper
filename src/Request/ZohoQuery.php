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
        if($columns == '*' || strtolower($columns) == 'all'){
            return true;
        }else {

        }
    }
}