<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 12/12/2016
 * Time: 9:33 AM
 */

namespace CatchZohoMapper\Request;


interface ZohoRequest
{
    public function make(array $params);
    // can be an instance of ZohoOperationParams or an array
    public function request($params);
    public function execute($params); // alias
    // options
    public function includeNull();
    public function triggerWorkflow();
    public function selectColumns($columns); // array or string
    public function fromIndex($index); // array or string
    public function toIndex($index); // array or string
    public function sortBy($columnsName); // string
    public function sortOrder($order); // Asc , Ascending , Desc, Descending
    public function ApprovalQueue();
    public function isApproval(); // alias
    public function parent();
    // user operation
    public function getAll();
    public function getAdmins();
    public function getActive();
    public function getInactive();
    public function getActiveConfirmedAdmins();
    // search operations
    public function select($columns); // array or string
    public function from($module); // array or string
    public function where(array $condition);
    public function orWhere(array $condition);
    public function orderBy($columnsName, $order); // alias
    public function limit($limit); // array or string
    public function offset($offset); // array or string

}