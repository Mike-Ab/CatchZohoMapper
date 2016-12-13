<?php

namespace CatchZohoMapper\ZohoCrm;

trait ZohoMethod
{
    /**
     * @param $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return $this
     */
    public function getMyRecords()
    {
        $this->setMethod('getMyRecord');
        return $this;
    }

    /**
     * @return $this
     */
    public function getRecords()
    {
        $this->setMethod('getRecords');
        return $this;
    }

    /**
     * @return $this
     */
    public function getRecordById()
    {
        $this->setMethod('getRecordById');
        return $this;
    }

    /**
     * @return $this
     */
    public function insertRecords()
    {
        $this->setMethod('insertRecords');
        return $this;
    }

    /**
     * @return $this
     */
    public function updateRecords()
    {
        $this->setMethod('updateRecords');
        return $this;
    }

    /**
     * @return $this
     */
    public function deleteRecord()
    {
        $this->setMethod('deleteRecord');
        return $this;
    }

    /**
     * @return $this
     */
    public function getFields()
    {
        $this->setMethod('getFields');
        return $this;
    }

    /**
     * @return $this
     */
    public function getRelatedRecords()
    {
        $this->setMethod('getRelatedRecords');
        return $this;
    }

    /**
     * @return $this
     */
    public function uploadFile()
    {
        $this->setMethod('uploadFile');
        return $this;
    }

    /**
     * @return $this
     */
    public function downloadFile()
    {
        $this->setMethod('downloadFile');
        return $this;
    }

    /**
     * @return $this
     */
    public function deleteFile()
    {
        $this->setMethod('deleteFile');
        return $this;
    }

    /**
     * @return $this
     */
    public function searchRecords()
    {
        $this->setMethod('searchRecords');
        return $this;
    }


}