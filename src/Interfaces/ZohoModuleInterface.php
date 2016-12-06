<?php

namespace CatchZohoMapper\Interfaces;


interface ZohoModuleInterface
{
    public function getMyRecords(); //
    public function getRecords(); //
    public function getRecordById(); //
    public function insertRecords(); //
    public function updateRecords(); //
    public function deleteRecords();
    public function getFields(); //
    public function getRelatedRecords(); //
    public function updateRelatedRecords();
    public function uploadFile();//
    public function downloadFile();
    public function deleteFile();
    public function searchRecords();//
}