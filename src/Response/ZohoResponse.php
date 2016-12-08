<?php
/**
 * Created by PhpStorm.
 * User: Moe
 * Date: 15/11/16
 * Time: 4:30 PM
 */

namespace CatchZohoMapper\Response;


use CatchZohoMapper\Interfaces\ZohoResponseOperationsInterface;
use CatchZohoMapper\Module\ZohoModule;
use CatchZohoMapper\ZohoErrors;


class ZohoResponse implements ZohoResponseOperationsInterface
{
    use ResponseOperations;

    /**
     * @var bool|int
     */
    private $apiVersion = 2;

    /**
     * What did zoho say
     *
     * @var $responseObject
     */
    private $responseObject;

    /**
     * @var $response
     */
    protected $response;

    /**
     * @var $result
     */
    protected static $result = true;

    /**
     * @var $message
     */
    protected $message;

    /**
     * @var $uri
     */
    protected $uri;

    /**
     * @var $record
     */
    protected $recordDetails;

    /**
     * @var $record
     */
    protected $recordType;

    /**
     * @var null
     */
    protected $module = null;

    /**
     * ZohoResponse constructor.
     *
     * @param null|string $responseObject Json
     * @param bool|string $recordType
     * @param bool|int $responseVersion
     */
    public function __construct($responseObject = null, $recordType = false, $responseVersion = false)
    {
        if (isset($responseObject)){
            $this->responseObject =$responseObject;
        }
        if ($recordType){
            $this->recordType = $recordType;
        }
        if ($responseVersion){
            $this->apiVersion = $responseVersion;
        }
    }

    /**
     * @return mixed
     */
    public function getRecordDetails()
    {
        return $this->recordDetails;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return self::$result;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return null|ZohoModule
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Handling the response
     *
     * @param bool $responseObject
     * @param bool $recordType
     * @return ZohoResponse
     */
    public function handleResponse($responseObject = false, $recordType = false)
    {

        if ($responseObject) {
            $this->responseObject = $responseObject;
        }
        if ($recordType) {
            $this->recordType = $recordType;
        }
        $operation = debug_backtrace()[2]['function'];
        $this->responseObject = (json_decode($this->responseObject, true));
        self::checkForErrors($this->responseObject);
        return $this->parse($operation);
    }

    /**
     * Populate the values to be returned
     *
     * @param ZohoResponseParser $parsedResponse
     * @return $this
     */
    private function populateResponse(ZohoResponseParser $parsedResponse)
    {
        $this->response = $parsedResponse->response;
        $this->recordDetails = $parsedResponse->parsedResponse['recordDetails'];
        $this->message = $parsedResponse->parsedResponse['message'];
        $this->uri = $parsedResponse->parsedResponse['uri'];
        $this->module = $parsedResponse->module;
        self::$result = $parsedResponse->result;
        return $this;
    }

    /**
     * Parse based on the operation
     *
     * @param $operation
     * @return ZohoResponse
     * @throws \Exception
     */
    private function parse($operation)
    {
        if(!method_exists ($this, $operation)){
            throw new \Exception('Invalid operation :'.$operation);
        }
        return $this->populateResponse(
            $this->{$operation}()
        );
    }

    /**
     * Check general errors in the response
     * Mostly built around v2 api responses
     *
     * @param $response
     * @throws \Exception
     */
    private static function checkForErrors($response)
    {
        if (isset($response['response']['error']) || isset ($response['response']['nodata'])) {
            $code = isset($response['response']['error']['code']) ?
                $response['response']['error']['code'] : $response['response']['nodata']['code'];
            self::$result = false;
            throw new \Exception(ZohoErrors::getMessage($code), $code);
        }
        self::$result = true;
    }

}