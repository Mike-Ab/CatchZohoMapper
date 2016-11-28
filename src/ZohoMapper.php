<?php
namespace CatchZohoMapper;

use CatchZohoMapper\Traits\ZohoModuleOperations;

class ZohoMapper
{
    use ZohoModuleOperations;

    private $token;
    private $url = null;
    protected $recordType;

    public function __construct($token, $recordType)
    {
        if (!$token) {
            throw new \Exception('Missing auth token from Zoho');
        }
        if (!$recordType) {
            throw new \Exception('Missing record type.. ex \'Leads\'');
        }
        $this->token = $token;
        $this->recordType = $recordType;
    }

    public function getUsers($type = 'AllUsers')
    {
        $options = (new ZohoOperationParams($this->token))
            ->setUserType($type);
        return ((new ZohoResponse)->handleResponse(
            $this->execute($options))
        );
    }

}
