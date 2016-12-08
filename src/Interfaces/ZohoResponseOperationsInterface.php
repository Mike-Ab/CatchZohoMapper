<?php

namespace CatchZohoMapper\Interfaces;


interface ZohoResponseOperationsInterface
{
    public function getMyRecords(); //
    public function getRecords(); //
    public function getRecordById(); //
    public function insertRecords(); //
    public function updateRecords(); //
    public function deleteRecords();
    public function getFields(); //
    public function getRelatedRecords(); //
    public function uploadFile();//
    public function downloadFile();//
    public function deleteFile();//
    public function searchRecords();//
    public function getUsers();//
    public function getModules();//

    /**
     * @TODO implement updateRelatedRecords
     */
//    public function updateRelatedRecords();
}