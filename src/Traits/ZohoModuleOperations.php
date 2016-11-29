<?php
namespace CatchZohoMapper\Traits;

use CatchZohoMapper\ZohoOperationParams;
use CatchZohoMapper\ZohoResponse;
use CatchZohoMapper\ZohoServiceProvider as Zoho;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

trait ZohoModuleOperations
{

    public function insertRecords($record, $isApproval = false)
    {
        $options = (new ZohoOperationParams($this->token))
            ->setXmlData(Zoho::generateXML($record, $this->recordType));
        if (!$isApproval) {
            $options->setIsApproval('false');
        }
        return ((new ZohoResponse())->handleResponse(
            $this->execute($options))
        );
    }

    public function updateRecords($recordId, array $updates)
    {
        $options = (new ZohoOperationParams($this->token))
            ->setId($recordId)
            ->setXmlData(Zoho::generateXML($updates, $this->recordType));
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options))
        );
    }

    public function getRecordById($recordId, $includeNull = false)
    {
        $options = (new ZohoOperationParams($this->token))
            ->setId($recordId)
            ->setWfTrigger(null);
        if ($includeNull) {
            $options->setNewFormat(1);
        }
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options))
        );
    }

    /**
     * Provides ZohoModule instace containing all the module details
     * accessible by getModule(). Also provides the fileds in RecordDetails
     *
     * @param bool $mandatory
     * @return ZohoResponse
     */
    public function getFields($mandatory = false)
    {
        $options = (new ZohoOperationParams($this->token))
            ->setWfTrigger(null);
        if ($mandatory) {
            $options->setType(2);
        }
        return (new ZohoResponse)->handleResponse(
            $this->execute($options), $this->recordType);
    }


    private function execute(ZohoOperationParams $params, $method = 'POST')
    {
        try {
            $http = new Client(['verify' => false]);
            $attempt = $http->request('POST', Zoho::generateURL($this->recordType), [
                'form_params' => $params::getParams()
            ]);
            return ($attempt->getBody());
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    private function sendFile(ZohoOperationParams $options)
    {
        try {
            $http = new Client(['verify' => false]);
            $attempt = $http->request('POST', Zoho::generateURL($this->recordType), [
                'multipart' => [
                    [
                        'name' => 'id',
                        'contents' => $options::getId()
                    ],
                    [
                        'name' => 'authtoken',
                        'contents' => $options::getAuthtoken()
                    ],
                    [
                        'name' => 'scope',
                        'contents' => $options::getScope()
                    ],
                    [
                        'Content-type' => 'multipart/form-data',
                        'name' => 'content',
                        'contents' => fopen($options::getContent(), 'r')
                    ]
                ]
            ]);
            return ($attempt->getBody());
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}