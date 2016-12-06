<?php
namespace CatchZohoMapper\Traits;

use CatchZohoMapper\ZohoErrors;
use CatchZohoMapper\ZohoModule;
use CatchZohoMapper\ZohoOperationParams;
use CatchZohoMapper\ZohoResponse;
use CatchZohoMapper\ZohoServiceProvider as Zoho;

trait ZohoRecordOperations
{
    /**
     * Insert record or an array of records
     * Available opts : 'newFormat|includeNull', 'wfTrigger', 'duplicateCheck', 'isApproval', 'version', 'checkMandatory'
     *
     * @param $record
     * @param bool $opts
     * @param bool $checkMandatory
     * @return ZohoResponse
     */
    public function add(array $record, $opts = false, $checkMandatory = false)
    {
        if ($checkMandatory) {
            $this->checkMandatory($record);
        }
        if ($opts){
            if (array_key_exists('checkMandatory', $opts)){
                $this->checkMandatory($record);
            }
        }
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setXmlData(Zoho::generateXML($record, $this->recordType));
        if (array_key_exists(0,$record)) {
            if (is_array($record[0])) {
                $options->setVersion(4);
            }
        }
        if ($opts) {
            $options = $this->setOpts($options, $opts);
            if (array_key_exists('duplicateCheck', $opts)){
                if ($opts['duplicateCheck']){
                    $options->setVersion(4);
                }
            }
        }
        return Zoho::execute($options);
    }

    /**
     * Update an existing record
     * Available opts : 'newFormat|includeNull', 'wfTrigger', 'version'
     * Updates can be passed as 'string:$recordIds', 'array:$updates' | 'string:$recordIds', 'array:$updates' | 'associated array:$recordIds'
     *
     * @param $recordIds
     * @param array $updates
     * @param bool $opts
     * @return ZohoResponse
     * @throws \Exception
     */
    public function save($recordIds, array $updates = [], $opts = false)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setNewFormat(null);
        if (is_array($recordIds)){
            $formedUpdates = [];
            if(count($updates) === 0) {
                foreach ($recordIds as $id => $updateArray) {
                    if (!is_array($updateArray)) {
                        throw new \Exception('Updates should be passed as an associated array "Field Name" => "New Value ..."');
                    }
                    $updateArray['Id'] = $id;
                    $formedUpdates[] = $updateArray;
                }
                $options->setVersion(4);
            }else { // count of $updates param is not 0
                foreach ($recordIds as $id) {
                    $updates['Id'] = $id;
                    $formedUpdates[] = $updates;
                }
                if (count($recordIds) == 1){
                    $options->setId($recordIds[0]);
                }else {
                    $options->setVersion(4);
                }
            }
            $options->setXmlData(Zoho::generateXML($formedUpdates, $this->recordType));
        }else {
            $options->setId($recordIds)
                ->setXmlData(Zoho::generateXML($updates, $this->recordType));
        }
        if ($opts) {
            $options = $this->setOpts($options, $opts);
        }
        return Zoho::execute($options);
    }

    /**
     * Delete a single Record
     *
     * @param $recordId
     * @return ZohoResponse
     */
    public function delete($recordId)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setWfTrigger(null)
            ->setId($recordId);
        return Zoho::execute($options);
    }

    /**
     * Get the instantiated module type related to the parent type defined as a param
     * Available opts : 'newFormat|includeNull', 'fromIndex', 'toIndex'
     *
     * @param $parentModule
     * @param $parentId
     * @param bool $opts
     * @return ZohoResponse
     */
    public function getRelatedRecords($parentModule, $parentId, $opts = false)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setParentModule($parentModule)
            ->setId($parentId);
        if ($opts) {
            $options = $this->setOpts($options, $opts);
        }
        return Zoho::execute($options);
    }

    public function fetch($recordIds, $includeNull = false)
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
        return Zoho::execute($options);
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
        if (array_key_exists('duplicateCheck', $opts)) {
            if (!$opts['duplicateCheck']){
                $options->setDuplicateCheck(null);
            }else {
                if (strtolower($opts['duplicateCheck']) == 'error'){
                    $options->setDuplicateCheck(1);
                }elseif (strtolower($opts['duplicateCheck']) == 'update'){
                    $options->setDuplicateCheck(2);
                }
            }
        }
        if (array_key_exists('version', $opts)) {
            $options->setVersion($opts['version']);
        }
        if (array_key_exists('wfTrigger', $opts)) {
            $options->setWfTrigger($opts['wfTrigger'] ? 'true' : 'false');
        }
        if (array_key_exists('isApproval', $opts)) {
            $options->setIsApproval($opts['isApproval'] ? 'true' : 'false');
        }
        if (array_key_exists('newFormat', $opts)) {
            $options->setNewFormat($opts['newFormat']);
        }
        if (array_key_exists('includeNull', $opts)) {
            $options->setNewFormat($opts['includeNull'] ? 1 : 2);
        }
        if (array_key_exists('fromIndex', $opts)){
            $options->setFromIndex($opts['fromIndex']);
        }
        if (array_key_exists('toIndex', $opts)){
            $fromIndex = array_key_exists('fromIndex', $opts) ? intval($opts['fromIndex']) : 1;
            if ( intval($opts['toIndex']) - $fromIndex > Zoho::maxGetRecords()){
                throw new \Exception('API allows a maximum of ' .Zoho::maxGetRecords(). ' records to be fetched per call');
            }
            $options->setToIndex($opts['toIndex']);
        }
        if (array_key_exists('selectColumns', $opts)){
            $options = $this->setSelectColumns($options, $opts['selectColumns']);
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

    private function checkMandatory ($record)
    {
        $mandatoryFields = ((new ZohoModule($this->recordType))->fetchMandatory($this->token));
        $mandatoryMissing = [];
        if (is_array($record) && count($record) > 1){
            foreach ($record as $innerRecord){
                foreach ($mandatoryFields as $field) {
                    if (!array_key_exists($field->getName(), $innerRecord)) {
                        $mandatoryMissing[] = $field->getName();
                    }
                }
            }
        }else {
            foreach ($mandatoryFields as $field) {
                if (!array_key_exists($field->getName(), $record)) {
                    $mandatoryMissing[] = $field->getName();
                }
            }
        }
        if (count($mandatoryMissing) > 0){
            throw new \Exception(ZohoErrors::$errors['4401'].': '.implode(', ', $mandatoryMissing), '4401');
        }
        return true;
    }
}
