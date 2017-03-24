<?php

namespace CatchZohoMapper\Interfaces;


class ZohoApiMethods implements ZohoModuleInterface
{
    /**
     * Check if the method exists in zoho api
     *
     * @param $methodName
     *
     * @return bool
     */
    public static final function isZohoMethod($methodName)
    {
        return method_exists(static::class, $methodName);
    }

    /**
     * @param $fileId
     */
    public function deleteFile($fileId)
    {
        //
    }

    /**
     * @param $id
     */
    public function deleteRecords($id)
    {
        //
    }

    /**
     * @param $fileId
     */
    public function downloadFile($fileId)
    {
        //
    }

    /**
     * @param $mandatory
     */
    public function getFields($mandatory)
    {
        //
    }

    /**
     * @param $opts
     */
    public function getMyRecords($opts)
    {
        //
    }

    /**
     * @param $recordIds
     * @param $includeNull
     */
    public function getRecordById($recordIds, $includeNull)
    {
        //
    }

    /**
     * @param $opts
     */
    public function getRecords($opts)
    {
        //
    }

    /**
     * @param $parentModule
     * @param $parentId
     * @param $opts
     */
    public function getRelatedRecords($parentModule, $parentId, $opts)
    {
        //
    }

    /**
     * @param array $record
     * @param $opts
     * @param $checkMandatory
     */
    public function insertRecords(array $record, $opts, $checkMandatory)
    {
        //
    }

    /**
     * @param array $searchCriteria
     * @param $opts
     */
    public function searchRecords(array $searchCriteria, $opts)
    {
        //
    }

    /**
     * @param $recordIds
     * @param array $updates
     * @param $opts
     */
    public function updateRecords($recordIds, array $updates, $opts)
    {
        //
    }

    /**
     * @param $recordId
     * @param $filePath
     * @param $attachmentUrl
     */
    public function uploadFile($recordId, $filePath, $attachmentUrl)
    {
        //
    }

}