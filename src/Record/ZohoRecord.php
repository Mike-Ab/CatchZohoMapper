<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 11/28/2016
 * Time: 10:56 AM
 */

namespace CatchZohoMapper\Record;

use CatchZohoMapper\Interfaces\ZohoRecordInterface;


class ZohoRecord implements ZohoRecordInterface
{
    use ZohoRecordOperations;

    protected $token = false;
    protected $recordType = false;

    public function __construct($token, $recordType, $recordId = false)
    {
        $this->token = $token;
        $this->recordType = $recordType;
        if($recordId){
            $this->setId($recordId);
        }
    }

}