<?php
namespace CatchZohoMapper\Traits;

use CatchZohoMapper\Module\ZohoModule;
use CatchZohoMapper\Response\ZohoResponse;
use CatchZohoMapper\ZohoErrors;
use CatchZohoMapper\ZohoOperationParams;
use CatchZohoMapper\ZohoServiceProvider as Zoho;

trait ZohoModuleOperations
{
    use OptionsParser;
    /**
     * Insert record or an array of records
     * Available opts : 'newFormat|includeNull', 'wfTrigger', 'duplicateCheck', 'isApproval', 'version', 'checkMandatory'
     *
     * @param $record
     * @param bool|array $opts
     * @param bool $checkMandatory
     * @return ZohoResponse
     */
    public function insertRecords(array $record, $opts = false, $checkMandatory = false)
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
     * @param bool|array $opts
     * @return ZohoResponse
     * @throws \Exception
     */
    public function updateRecords($recordIds, array $updates = [], $opts = false)
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
    public function deleteRecords($recordId)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setWfTrigger(null)
            ->setId($recordId);
        return Zoho::execute($options);
    }

    /**
     * Delete a single Record
     *
     * @param $recordId
     * @return ZohoResponse
     */
    public function deleteFile($recordId)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setWfTrigger(null)
            ->setId($recordId);
        return Zoho::execute($options);
    }

    /**
     * Fetch records from ZOHO CRM.
     * Available opts : 'newFormat|includeNull', 'selectColumns', 'fromIndex', 'toIndex', 'sortColumnString',
     * 'sortOrderString', 'lastModifiedTime', 'version : only if you know what you are doing ... leave default value'
     *
     * @param bool|array $opts
     * @return ZohoResponse
     */
    public function getRecords($opts = false)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setWfTrigger(null);
        if ($opts) {
            $options = $this->setOpts($options, $opts);
        }
        return Zoho::execute($options);
    }

    /**
     * Fetch AuthToken's Owned records from ZOHO CRM.
     * Available opts : 'newFormat|includeNull', 'selectColumns', 'fromIndex', 'toIndex', 'sortColumnString',
     * 'sortOrderString', 'lastModifiedTime', 'version : only if you know what you are doing ... leave default value'
     *
     * @param bool|array $opts
     * @return ZohoResponse
     */
    public function getMyRecords($opts = false)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setWfTrigger(null);
        if ($opts) {
            $options = $this->setOpts($options, $opts);
        }
        return Zoho::execute($options);
    }

    /**
     * Get the instantiated module type related to the parent type defined as a @param $parentModule
     * Available opts : 'newFormat|includeNull', 'fromIndex', 'toIndex'
     *
     * @param $parentModule
     * @param $parentId
     * @param bool|array $opts
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

    /**
     * Get the record(s) from Zoho
     *
     * @param string|array $recordIds
     * @param bool $includeNull
     * @return ZohoResponse
     */
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
        return Zoho::execute($options);
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
