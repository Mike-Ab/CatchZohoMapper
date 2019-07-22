<?php

namespace CatchZohoMapper\Interfaces;


interface ZohoModuleInterface
{
    public function getMyRecords($opts); //
    public function getRecords($opts); //
    public function getRecordById($recordIds, $includeNull); //
    public function insertRecords(array $record, $opts, $checkMandatory); //
    public function updateRecords($recordIds, array $updates , $opts); //
    public function deleteRecords($id);
    public function getFields($mandatory); //
    public function getRelatedRecords($parentModule, $parentId, $opts); //
    public function uploadFile($recordId, $filePath , $attachmentUrl);//
    public function downloadFile($fileId);//
    public function deleteFile($fileId);//
    public function searchRecords(array $searchCriteria, $opts);//
    public function getModules();//
    public function getUsers();//
    /**
     * @TODO implement updateRelatedRecords
     */
//    public function updateRelatedRecords();
}