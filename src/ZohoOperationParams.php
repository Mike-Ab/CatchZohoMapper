<?php
/**
 * Created by PhpStorm.
 * User: Moe
 * Date: 16/11/16
 * Time: 11:49 AM
 */

namespace CatchZohoMapper;


class ZohoOperationParams
{
    /**
     * @var $scope
     */
    protected static $scope = 'crmapi';

    /**
     * @var $authtoken
     */
    protected static $authtoken;

    /**
     * newFormat=1:To exclude fields with "null" values while inserting data from your CRM account.
     * newFormat=2:To include fields with "null" values while inserting data from your CRM account.
     *
     * @var$newFormat
     */
    protected static $newFormat = 1;

    /**
     * @var null $wfTrigger
     */
    protected static $wfTrigger = 'true';

    /**
     * @var$xmlData
     */
    protected static $xmlData;

    /**
     * @var null $selectColumns
     */
    protected static $selectColumns = null;

    /**
     * @var null $fromIndex
     */
    protected static $fromIndex = null;

    /**
     * @var null $toIndex
     */
    protected static $toIndex = null;

    /**
     * @var null $sortColumnString
     */
    protected static $sortColumnString = null;

    /**
     * @var null $sortOrderString
     */
    protected static $sortOrderString = null;

    /**
     * @var int $version
     */
    protected static $version = 2;

    /**
     * @var null $isApproval
     */
    protected static $isApproval = null;

    /**
     * @var null $duplicateCheck
     */
    protected static $duplicateCheck = null;

    /**
     * @var null $id
     */
    protected static $id = null;

    /**
     * Related only to getUsers function
     *
     * @var string
     */
    protected static $type;

    /**
     * ZohoOperationParams constructor.
     * @param bool $authToken
     */
    public function __construct($authToken = false)
    {
        if ($authToken) {
            $this->setAuthtoken($authToken);
        }
    }

    /**
     * @param string $type
     * @return $this
     * @throws \Exception
     */
    public function setType($type)
    {
        if (!in_array($type, ZohoServiceProvider::allowedUserTypes())){
            throw new \Exception('Invalid users type parameter', 6003);
        }
        self::$type = $type;
        return $this;
    }

    /**
     * @param null $duplicateCheck
     */
    public function setDuplicateCheck($duplicateCheck)
    {
        self::$duplicateCheck = $duplicateCheck;
    }

    /**
     * @param null $isApproval
     */
    public function setIsApproval($isApproval)
    {
        self::$isApproval = $isApproval;
    }

    /**
     * @param $authtoken
     * @return $this
     */
    public function setAuthtoken($authtoken)
    {
        self::$authtoken = $authtoken;
        return $this;
    }

    /**
     * @param null $fromIndex
     * @return $this
     */
    public function setFromIndex($fromIndex)
    {
        self::$fromIndex = $fromIndex;
        return $this;
    }

    /**
     * @param mixed $newFormat
     * @return $this
     */
    public function setNewFormat($newFormat)
    {
        self::$newFormat = $newFormat;
        return $this;
    }

    /**
     * @param mixed $scope
     * @return $this
     */
    public function setScope($scope)
    {
        self::$scope = $scope;
        return $this;
    }

    /**
     * @param null $selectColumns
     * @return $this
     */
    public function setSelectColumns($selectColumns)
    {
        self::$selectColumns = $selectColumns;
        return $this;
    }

    /**
     * @param null $sortColumnString
     * @return $this
     */
    public function setSortColumnString($sortColumnString)
    {
        self::$sortColumnString = $sortColumnString;
        return $this;
    }

    /**
     * @param null $sortOrderString
     * @return $this
     */
    public function setSortOrderString($sortOrderString)
    {
        self::$sortOrderString = $sortOrderString;
        return $this;
    }

    /**
     * @param null $toIndex
     * @return $this
     */
    public function setToIndex($toIndex)
    {
        self::$toIndex = $toIndex;
        return $this;
    }

    /**
     * @param int $version
     * @return $this
     */
    public function setVersion($version)
    {
        self::$version = $version;
        return $this;
    }

    /**
     * @param null $wfTrigger
     * @return $this
     */
    public function setWfTrigger($wfTrigger)
    {
        self::$wfTrigger = $wfTrigger;
        return $this;
    }

    /**
     * @param mixed $xmlData
     * @return $this
     */
    public function setXmlData($xmlData)
    {
        self::$xmlData = $xmlData;
        return $this;
    }

    /**
     * @param null $id
     * @return $this
     */
    public function setId($id)
    {
        self::$id = $id;
        return $this;
    }
    /**
     * Generates prepared params for the call to Zoho
     *
     * @return array
     */
    public static function getParams()
    {
        $params = [];
        foreach (get_class_vars(self::class) as $name => $value){
            if (isset(self::$$name)) {
                $params[$name] = $value;
            }
        }
        return $params;
    }

}