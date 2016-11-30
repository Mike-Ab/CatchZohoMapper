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
        return ((new ZohoResponse)->handleResponse(
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

    public function getRecords($opts)
    {
        // $includeNull = false, array $columns = [], $fromIndex = false, $toIndex = false, sortColumnString = false, $sortOrder = false, $lastModifiedTime = false
        $options = (new ZohoOperationParams($this->token))
            ->setWfTrigger(null);
        if (array_key_exists('includeNull', $opts)) {
            $options->setNewFormat($opts['includeNull'] ? 1 : 2);
        }
        if (array_key_exists('fromIndex', $opts)){
            $options->setFromIndex($opts['fromIndex']);
        }
        if (array_key_exists('selectColumns', $opts)){
            if (is_array($opts['selectColumns'])){
                $selectColumns = $this->recordType.'('.implode(',', $opts['selectColumns']).')';
            }else {
                $selectColumns = $this->recordType."({$opts['selectColumns']})";
            }
            $options->setSelectColumns($selectColumns);
        }
        if (array_key_exists('toIndex', $opts)){
            $fromIndex = array_key_exists('fromIndex', $opts) ? intval($opts['fromIndex']) : 1;
            if ( intval($opts['toIndex']) - $fromIndex > Zoho::maxGetRecords()){
                throw new \Exception('API allows a maximum of ' .Zoho::maxGetRecords(). ' records to be fetched per call');
            }
            $options->setToIndex($opts['toIndex']);
        }
        if (array_key_exists('sortColumnString', $opts)){
            $options->setSortColumnString($opts['sortColumnString']);
            if (array_key_exists('sortOrderString', $opts)){
                if (strtolower($opts['sortOrderString']) != 'asc' && strtolower($opts['sortOrderString'] != 'desc')){
                    throw new \Exception('Invalid sort order value. Allowed values : asc , desc');
                }
                $options->setSortOrderString(strtolower($opts['sortOrderString']));
            }
        }
        if (array_key_exists('lastModifiedTime', $opts)){
            $options->setLastModifiedTime(date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $opts['lastModifiedTime']))));
        }
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options), $this->recordType)
        );
    }

    public function getRecordById($recordIds, $includeNull = false)
    {
        $options = (new ZohoOperationParams($this->token))
            ->setWfTrigger(null);
        if (is_array($recordIds)){
            $options->setIdList($recordIds);
        }else {
            $options->setId($recordIds);
        }
        if ($includeNull) {
            $options->setNewFormat(1);
        }
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options), $this->recordType)
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