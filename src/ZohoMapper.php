<?php
namespace CatchZohoMapper;

use GuzzleHttp\Client;
use CatchZohoMapper\ZohoServiceProvider as Zoho;
use GuzzleHttp\Exception\ClientException;

class ZohoMapper
{
    private $token;
    private $url = null;
    protected $recordType;
    
    public function __construct($token, $recordType) {
        if (!$token) {
            throw new \Exception('Missing auth token from Zoho');
        }
        if (!$recordType) {
            throw new \Exception('Missing record type.. ex \'Leads\'');
        }
        $this->token = $token;
        $this->recordType = $recordType;
    }
    
    public function insertRecord ($record, $isApproval = false)
    {
        $options = (new ZohoOperationParams($this->token))
            ->setXmlData(Zoho::generateXML($record, $this->recordType));
        if (!$isApproval) {
            $options->setIsApproval('false');
        }
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options))
        );
    }

    public function updateRecord ($recordId, array $updates)
    {
        $options = (new ZohoOperationParams($this->token))
            ->setId($recordId)
            ->setXmlData(Zoho::generateXML($updates, $this->recordType));
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options))
        );
    }

    public function getRecordById ($recordId, $includeNull = false)
    {
        $options = (new ZohoOperationParams($this->token))
            ->setId($recordId)
            ->setWfTrigger(null);
        if ($includeNull) {
            $options->setNewFormat(1);
        }
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options), $this->recordType)
        );
    }

    public function getUsers($type = 'AllUsers')
    {
        $options= (new ZohoOperationParams($this->token))
            ->setType($type)->setWfTrigger('false');
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options), $this->recordType)
        );
    }
    private function execute(ZohoOperationParams $params, $method = 'POST')
    {
        try {
            $http = new Client(['verify' => false]);
            $attempt = $http->request('POST', Zoho::generateURL($this->recordType), [
                'form_params' => $params::getParams()
            ]);
            return ($attempt->getBody());
        }catch (ClientException $e){
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }
}
