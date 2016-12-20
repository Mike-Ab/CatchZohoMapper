<?php

namespace CatchZohoMapper;


use CatchZohoMapper\Request\ZohoQuery;
use CatchZohoMapper\ZohoCrm\ZohoMethod;

class ZohoCrm
{
    use ZohoMethod, ZohoQuery;

    private $recordType;
    private $authToken;
    private $checkModule;
    protected $method;
    protected $params;
    protected $zoho;
    
    public function __construct($authToken, $recordType = false)
    {
        $this->authToken = $authToken;
        if ($recordType){
            $this->recordType = $recordType;
        }
        $this->make([]);
    }

    /**
     * Create the instances
     *
     * @param array $opts
     * @param bool $checkModule
     * @return $this
     */
    public function make(array $opts, $checkModule = false)
    {
        foreach ($opts as $opt => $value){
            $this->{$opt} = $value;
        }
        if ($checkModule){
            $this->checkModule = true;
        }
        $this->zoho = new ZohoMapper($this->authToken, $this->recordType, $this->checkModule);
        $this->params = new ZohoOperationParams($this->authToken, $this->recordType);
        return $this;
    }

    public function request()
    {
        if (!$this->method) {
            throw new \Exception('Zoho CRM method is not identified');
        }
        return $this->zoho->{$this->method}($this->params);
    }

    public function execute()
    {
        return $this->request();
    }

    public function get()
    {
        return $this->request();
    }

    public function setRecordType($recordType)
    {
        $this->recordType = $recordType;
        return $this;
    }

    public function setModule($recordType)
    {
        return $this->setRecordType($recordType);
    }

}