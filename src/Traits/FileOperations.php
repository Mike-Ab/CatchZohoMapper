<?php
namespace CatchZohoMapper\Traits;


use CatchZohoMapper\ZohoOperationParams;
use CatchZohoMapper\ZohoServiceProvider as Zoho;
use GuzzleHttp\Psr7\Stream;

trait FileOperations
{
    /**
     * Upload a file to be attached to a record
     *
     * @param $recordId
     * @param $filePath
     * @param bool $attachmentUrl
     * @return mixed
     * @throws \Exception
     */
    public function uploadFile($recordId, $filePath , $attachmentUrl = false)
    {
        if (!is_file ($filePath) || !file_exists ($filePath)){
            throw new \Exception('The file you are attempting to upload is missing');
        }
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setId($recordId)
            ->setWfTrigger(null)
            ->setNewFormat(null)
            ->setVersion(null);
        if (!$attachmentUrl){
            $options->setContent($filePath);
            return Zoho::sendFile($options);
        }else {
            $options->setAttachmentUrl($attachmentUrl);
            return Zoho::execute($options);
        }
    }

    /**
     * Returns the binary content of the file
     *
     * @param $attachmentId
     * @return Stream
     */
    public function downloadFile($attachmentId)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setId($attachmentId);
        return Zoho::request($options)->getContents();
    }
}
