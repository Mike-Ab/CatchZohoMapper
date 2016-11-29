<?php
namespace CatchZohoMapper;

use CatchZohoMapper\Traits\ZohoModuleOperations;

class ZohoMapper
{
    use ZohoModuleOperations;

    private $token;
    private $url = null;
    protected $recordType;

    public function __construct($token, $recordType, $check = false)
    {
        if (!$token) {
            throw new \Exception('Missing auth token from Zoho');
        }
        if (!$recordType) {
            throw new \Exception('Missing record type.. ex \'Leads\'');
        }
        $this->token = $token;
        $this->recordType = $recordType;
        if ($check && $recordType !== 'Info'){
            $test = false;
            $modules = $this->getModules()->getRecordDetails();
            array_walk($modules, function ($module) use (&$test, $recordType){
                if ($module['devName'] === $recordType){
                    $test = true;
                }
            });
            if (!$test) {
                throw new \Exception('Record type <strong>\''.$recordType.'\'</strong> not allowed');
            }
        }
    }

    public function getUsers($type = 'AllUsers')
    {
        $options = (new ZohoOperationParams($this->token))
            ->setUserType($type);
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options))
        );
    }

    public function getModules($apiOnly = false)
    {
        $options = (new ZohoOperationParams($this->token));
        if ($apiOnly) {
            $options->setUserType('api');
        }
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options))
        );
    }

    public function uploadFile($recordId, $filePath , $attachmentUrl = false)
    {
        if (!is_file ($filePath) || !file_exists ($filePath)){
            throw new \Exception('The file you are attempting to upload is missing');
        }
        $options = (new ZohoOperationParams($this->token))
            ->setId($recordId)
            ->setWfTrigger(null)
            ->setNewFormat(null)
            ->setVersion(null);
        if (!$attachmentUrl){
            $options->setContent($filePath);
            return (new ZohoResponse)->handleResponse(
                $this->sendFile($options));
        }else {
            $options->setAttachmentUrl($attachmentUrl);
            return (new ZohoResponse)->handleResponse(
                $this->execute($options));
        }
    }

}
