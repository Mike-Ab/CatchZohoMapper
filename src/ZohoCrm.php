<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 12/13/2016
 * Time: 9:51 AM
 */

namespace CatchZohoMapper;


use CatchZohoMapper\ZohoCrm\ZohoMethod;

class ZohoCrm
{
    use ZohoMethod;

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
    }

    /**
     * Create the instances
     *
     * @param array $opts
     * @return $this
     */
    public function make(array $opts, $checkModule = false)
    {
        foreach ($opts as $opt => $value){
            $this->{$opt} = $value;
        }
        $this->zoho = new ZohoMapper($this->authToken, $this->recordType, $this->checkModule);
        $this->params = new ZohoOperationParams($this->authToken, $this->recordType);
        return $this;
    }

    public function request()
    {
        return $this->zoho->{$this->method}($this->params);
    }

    public function execut()
    {
        return $this->request();
    }

}