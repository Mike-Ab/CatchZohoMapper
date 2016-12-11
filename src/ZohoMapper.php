<?php
namespace CatchZohoMapper;

use CatchZohoMapper\Interfaces\ZohoModuleInterface;
use CatchZohoMapper\Response\ZohoResponse;
use CatchZohoMapper\Traits\FileOperations;
use CatchZohoMapper\Traits\Singleton;
use CatchZohoMapper\Traits\ZohoModuleOperations;
use CatchZohoMapper\ZohoServiceProvider as Zoho;

class ZohoMapper implements ZohoModuleInterface
{
    use ZohoModuleOperations, FileOperations, Singleton;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    protected $recordType;

    /**
     * ZohoMapper constructor.
     *
     * @param string $token
     * @param string $recordType
     * @param bool $check Ensures that the recordType is supported by the API
     * @throws \Exception
     */
    public function __construct($token, $recordType, $check = false)
    {
        if (!$token) {
            throw new \Exception('Missing auth token from Zoho');
        }
        if (!$recordType) {
            throw new \Exception('Missing record type.. ex \'Leads\'');
        }
        $this->token = $token;
        $this->recordType = ucwords($recordType);
        if ($check && ucwords($recordType) !== 'Info'){
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

    /**
     * Get users of your account
     * Allowed types : 'AllUsers', 'ActiveUsers', 'DeactiveUsers', 'AdminUsers', 'ActiveConfirmedAdmins'
     *
     * @param string $type
     * @return ZohoResponse
     */
    public function getUsers($type = 'AllUsers')
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setUserType($type);
        return Zoho::execute($options);
    }

    /**
     * Get a list of the modules defined in your CRM
     *
     * @param bool $apiOnly Return a list of the API supported modules only
     * @return ZohoResponse
     */
    public function getModules($apiOnly = false)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType));
        if ($apiOnly) {
            $options->setUserType('api');
        }
        return Zoho::execute($options);
    }

    /**
     * Search for records
     *
     * @param array $searchCriteria
     * @param bool $opts
     * @return ZohoResponse
     */
    public function searchRecords(array $searchCriteria, $opts = false)
    {
        $options = (new ZohoOperationParams($this->token, $this->recordType))
            ->setWfTrigger(null)
            ->setVersion(null);
        if ($opts) {
            $options = $this->setOpts($options, $opts);
        }
        $options->setCriteria(Zoho::formSearchCriteria($searchCriteria));
        var_dump(Zoho::formSearchCriteria($searchCriteria));
        return Zoho::execute($options);
    }

}
