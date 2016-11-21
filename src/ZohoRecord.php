<?php
/**
 * Created by PhpStorm.
 * User: Moe
 * Date: 16/11/16
 * Time: 11:47 AM
 */

namespace CatchZohoMapper;


interface ZohoRecord
{
    public function getMyRecords();
    public function getRecords();
    public function getRecordById();
    public function insertRecords();
    public function updateRecords();
    public function deleteRecords();
    public function convertLead();
    public function getFields();
    public function getUsers();
    public function uploadFile();
    public function downloadFile();
    public function deleteFile();
    public function getModules();
    public function searchRecords();
}