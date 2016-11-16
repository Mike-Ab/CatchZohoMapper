<?php
/**
 * Created by PhpStorm.
 * User: Moe
 * Date: 15/11/16
 * Time: 4:30 PM
 */

namespace CatchZohoMapper;


class ZohoResponse
{
    /**
     * @var $response
     */
    protected $response;
    /**
     * @var $result
     */
    protected static $result;
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


    public function __construct($responseObject = null)
    {

    }

    public function getRecord()
    {
        return $this->recordDetails;
    }

    public function success()
    {
        return self::$result;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function handleResponse($responseObject, $recordType = false)
    {
        $operation = debug_backtrace()[1]['function'];
        $response = (json_decode($responseObject, true));
        self::checkForErrors($response);
        switch ($operation) {
            case 'insertRecord':
                return $this->populateResponse($this->formResponseArray($response));
                break;
            case 'updateRecord':
                return $this->populateResponse($this->formResponseArray($response));
                break;
            case 'getRecordById':
                return $this->populateResponse(
                    $this->formGetByIdResponseArray($response, $recordType));
                break;
            case 'getUsers':
                return $this->populateResponse(
                    $this->formGetUsersResponseArray($response, $recordType));
                break;
            default:
                throw new \Exception('Invalide operation "'.$operation.'""');
                break;
        }
    }

    private function populateResponse(array $responseArray)
    {
        $this->response = $responseArray;
        foreach ($responseArray as $key => $val) {
            $this->$key = $val;
        }
        return $this;
    }

    private function formGetByIdResponseArray(array $response, $recordType)
    {
        $response['message'] = 'Record(s) fetched successfully';
        $response['uri'] = $response['response']['uri'];
        array_walk($response['response']['result'][$recordType]['row']['FL'],
            function ($details) use (&$response) {
                $response['recordDetails'][$details['val']] = $details['content'];
            });
        unset($response['response']);
        $response['response'] = $response;
        return $response;
    }

    private function formGetRecordsResponseArray(array $response, $recordType)
    {
        $response['message'] = 'Record(s) fetched successfully';
        $response['uri'] = $response['response']['uri'];
        foreach ($response['response']['result'][$recordType] as $row){
            array_walk($row['FL'], function ($details) use (&$response, $row) {
                    $response['recordDetails'][$row['no']][$details['val']] = $details['content'];
                });
        }

        unset($response['response']);
        $response['response'] = $response;
        return $response;
    }

    private function formGetUsersResponseArray(array $response)
    {
        $response['message'] = 'Record(s) fetched successfully';
        $response['uri'] = $response['users']['orgid'];
        $response['recordDetails'] = $response['users']['user'];
        unset($response['response']);
        $response['response'] = $response;
        return $response;
    }

    private function formResponseArray(array $response)
    {
        $response['message'] = $response['response']['result']['message'];
        $response['uri'] = $response['response']['uri'];
        array_walk($response['response']['result']['recorddetail']['FL'],
            function ($details) use (&$response) {
                $response['recordDetails'][$details['val']] = $details['content'];
            });
        unset($response['response']);
        $response['response'] = $response;
        return $response;
    }

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