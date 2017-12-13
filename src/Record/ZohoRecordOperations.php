<?php
namespace CatchZohoMapper\Record;

use CatchZohoMapper\Traits\Commons;
use CatchZohoMapper\Traits\FileOperations;
use CatchZohoMapper\Traits\ZohoModuleOperations;
use CatchZohoMapper\CatchZohoCRM;
use CatchZohoMapper\Response\ZohoResponse;
use CatchZohoMapper\ZohoServiceProvider as Zoho;


trait ZohoRecordOperations
{
    use ZohoModuleOperations, Record,  FileOperations, Commons;

    /**
     * Insert the record in Zoho
     * Available opts : 'newFormat|includeNull', 'wfTrigger', 'duplicateCheck', 'isApproval', 'version', 'checkMandatory'
     * @param bool $opts
     * @param bool $checkMandatory
     * @return $this
     * @throws \Exception
     */
    public function insert($opts = false, $checkMandatory = false)
    {
        try {
            $attempt = $this->insertRecords($this->toArray(), $opts, $checkMandatory);
            $this->setId($attempt->getRecordDetails()['Id']);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this;
    }


    /**
     * Update the record in Zoho
     * Available opts : 'newFormat|includeNull', 'wfTrigger', 'version'
     * Updates can be passed as 'string:$recordIds', 'array:$updates' | 'string:$recordIds', 'array:$updates' | 'associated array:$recordIds'
     *
     * @param bool $opts
     * @return $this
     * @throws \Exception
     */
    public function save($opts = false)
    {
        try {
            $this->updateRecords($this->checkId(), Zoho::cleanRecordForUpdate($this->toArray()), $opts);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function delete()
    {
        try {
            $this->deleteRecords($this->checkId());
        }catch (\Exception $e){
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this;
    }


    /**
     * Populate the record with the values from Zoho
     *
     * @param bool $includeNull
     * @return $this
     * @throws \Exception
     */
    public function fetch($includeNull = false)
    {
        try {
            return $this->populate($this->getRecordById($this->checkId(), $includeNull)->getRecordDetails());
        }catch (\Exception $e){
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Provides ZohoModule instance containing all the module details
     * accessible by getModule(). Also provides the fields in RecordDetails
     *
     * @return array
     */
    public function describe()
    {
        return $this->getFields()->getModule()->describe();
    }

    /**
     * @param $filePath
     * @param bool $attachmentUrl
     *
     * @return $this
     * @throws \Exception
     */
    public function attachFile($filePath, $attachmentUrl = false)
    {
        try {
            $this->uploadFile($this->checkId(), $filePath, $attachmentUrl);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $this;
    }

    /**
     * Gets all the attachments for the record
     * Note that this method does NOT allow chaining methods after
     *
     * @return mixed
     */
    public function getAttachments()
    {
        return (new CatchZohoCRM($this->token, 'Attachments'))
        ->getRelatedRecords($this->recordType, $this->checkId())->getRecordDetails();
    }

    /**
     * Gets all the notes for the record
     * Note that this method does NOT allow chaining methods after
     *
     * @return mixed
     */
    public function getNotes()
    {
        return (new CatchZohoCRM($this->token, 'Notes'))
            ->getRelatedRecords($this->recordType, $this->checkId())->getRecordDetails();
    }

    /**
     * Get any related records described
     * @param string $relatedRecordType (Required Zoho Original Module Name)
     *
     * @return array
     */
    public function getRelated($relatedRecordType)
    {
        return (new CatchZohoCRM($this->token, $relatedRecordType))
            ->getRelatedRecords($this->recordType, $this->checkId())
            ->getRecordDetails();
    }

    /**
     * Check if the record has its ID set
     *
     * @return mixed
     * @throws \Exception
     */
    private function checkId()
    {
        if (!$this->has('id')){
            throw new \Exception('Missing record id. This operation requires an id to be set on the record.', '1001');
        }
        return $this->getId();
    }
}
