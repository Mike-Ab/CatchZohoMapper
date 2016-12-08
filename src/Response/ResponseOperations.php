<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 12/8/2016
 * Time: 10:11 AM
 */

namespace CatchZohoMapper\Response;


trait ResponseOperations
{
    /**
     * @return ZohoResponseParser
     */
    public function insertRecords()
    {
        return $this->apiVersion === 4 ?
            (new ZohoResponseParser($this->responseObject, $this->recordType))->formV4ResponseArray() :
            (new ZohoResponseParser($this->responseObject, $this->recordType))->formResponseArray() ;
    }

    /**
     * @return ZohoResponseParser
     */
    public function updateRecords()
    {
        return $this->insertRecords();
    }

    /**
     * @return ZohoResponseParser
     */
    public function uploadFile()
    {
        return (new ZohoResponseParser($this->responseObject, $this->recordType))
            ->formResponseArray();
    }

    /**
     * @return ZohoResponseParser
     */
    public function deleteRecords()
    {
        return (new ZohoResponseParser($this->responseObject, $this->recordType))
            ->formDeleteResponseArray();
    }

    /**
     * @return ZohoResponseParser
     */
    public function deleteFile()
    {
        return (new ZohoResponseParser($this->responseObject, $this->recordType))
            ->formDeleteFileResponseArray();
    }

    /**
     * @return ZohoResponseParser
     */
    public function getRecordById()
    {
        return $this->getRecords();
    }

    /**
     * @return ZohoResponseParser
     */
    public function getRecords()
    {
        return (new ZohoResponseParser($this->responseObject, $this->recordType))
            ->formGetRecordsResponseArray();
    }

    /**
     * @return ZohoResponseParser
     */
    public function getMyRecords()
    {
        return $this->getRecords();
    }

    /**
     * @return ZohoResponseParser
     */
    public function getRelatedRecords()
    {
        return $this->getRecords();
    }

    /**
     * @return ZohoResponseParser
     */
    public function searchRecords()
    {
        return $this->getRecords();
    }

    /**
     * @return ZohoResponseParser
     */
    public function getUsers()
    {
        return (new ZohoResponseParser($this->responseObject, $this->recordType))
            ->formGetUsersResponseArray();
    }

    /**
     * @return ZohoResponseParser
     */
    public function getFields()
    {
        return (new ZohoResponseParser($this->responseObject, $this->recordType))
            ->formGetFieldsResponseArray();
    }

    /**
     * @return ZohoResponseParser
     */
    public function getModules()
    {
        return (new ZohoResponseParser($this->responseObject, $this->recordType))
            ->formGetModuleResponseArray();
    }

    /**
     * @return null
     */
    public function downloadFile()
    {
        return null;
    }
}