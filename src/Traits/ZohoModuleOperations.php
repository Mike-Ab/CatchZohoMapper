<?php
namespace CatchZohoMapper\Traits;

use CatchZohoMapper\ZohoOperationParams;
use CatchZohoMapper\ZohoResponse;
use CatchZohoMapper\ZohoServiceProvider as Zoho;

trait ZohoModuleOperations
{

    public function insertRecords($record, $isApproval = false)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setXmlData(Zoho::generateXML($record, $this->recordType));
        if (!$isApproval) {
            $options->setIsApproval('false');
        }
        return ((new ZohoResponse)->handleResponse(
            Zoho::execute($options))
        );
    }

    public function updateRecords($recordId, array $updates)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setId($recordId)
            ->setXmlData(Zoho::generateXML($updates, $this->recordType));
        return ((new ZohoResponse)->handleResponse(
            Zoho::execute($options))
        );
    }

    public function getRecords($opts = false)
    {
        // $includeNull = false, array $columns = [], $fromIndex = false, $toIndex = false, sortColumnString = false, $sortOrder = false, $lastModifiedTime = false
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setWfTrigger(null);
        if ($opts) {
            $options = $this->setOpts($options, $opts);
        }
        return ((new ZohoResponse)->handleResponse(
            Zoho::execute($options), $this->recordType)
        );
    }

    public function getRecordById($recordIds, $includeNull = false)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
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
            Zoho::execute($options), $this->recordType)
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
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setWfTrigger(null);
        if ($mandatory) {
            $options->setType(2);
        }
        return (new ZohoResponse)->handleResponse(
            Zoho::execute($options), $this->recordType);
    }

    private function setSelectColumns(ZohoOperationParams $params, $selectColumns)
    {
        if (is_array($selectColumns)){
            $columns = $this->recordType.'('.implode(',', $selectColumns).')';
        }else {
            $columns = $this->recordType."($selectColumns)";
        }
        $params->setSelectColumns($columns);
        return $params;
    }

    private function setOpts (ZohoOperationParams $options, array $opts)
    {
        if (array_key_exists('includeNull', $opts)) {
            $options->setNewFormat($opts['includeNull'] ? 1 : 2);
        }
        if (array_key_exists('fromIndex', $opts)){
            $options->setFromIndex($opts['fromIndex']);
        }
        if (array_key_exists('selectColumns', $opts)){
            $options = $this->setSelectColumns($options, $opts['selectColumns']);
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
        return $options;
    }

}