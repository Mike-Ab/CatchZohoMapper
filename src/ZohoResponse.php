<?php
/**
 * Created by PhpStorm.
 * User: Moe
 * Date: 15/11/16
 * Time: 4:30 PM
 */

namespace CatchZohoMapper;


use GuzzleHttp\Pool;

class ZohoResponse
{
    private $apiVersion = 2;
    /**
     * @var $response
     */
    protected $response;
    /**
     * What did zoho say
     *
     * @var $responseObject
     */
    protected $responseObject;
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

    public function getRecordDetails()
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

    /**
     * @return null
     */
    public function getModule()
    {
        return $this->module;
    }

    public function handleResponse($responseObject = false, $recordType = false)
    {
        if (!$responseObject) {
            $responseObject = $this->responseObject;
        }
        if (!$recordType) {
            $recordType = $this->recordType;
        }
        $operation = debug_backtrace()[2]['function'];
        $response = (json_decode($responseObject, true));
        self::checkForErrors($response);
        switch ($operation) {
            case 'insertRecords':
                return $this->populateResponse( $this->apiVersion == 4 ?
                    $this->formV4ResponseArray($response) : $this->formResponseArray($response));
                break;
            case 'updateRecords':
                return $this->populateResponse($this->apiVersion == 4 ?
                    $this->formV4ResponseArray($response) : $this->formResponseArray($response));
                break;
            case 'uploadFile':
                return $this->populateResponse(
                    $this->formResponseArray($response));
                break;
            case 'deleteRecords':
                return $this->populateResponse(
                    $this->formDeleteResponseArray($response));
                break;
            case 'deleteFile':
                return $this->populateResponse(
                    $this->formDeleteFileResponseArray($response));
                break;
            case 'getRecordById':
                return $this->populateResponse(
                    $this->formGetRecordsResponseArray($response, $recordType));
                break;
            case 'getRecords':
                return $this->populateResponse(
                    $this->formGetRecordsResponseArray($response, $recordType));
                break;
            case 'getMyRecords':
                return $this->populateResponse(
                    $this->formGetRecordsResponseArray($response, $recordType));
                break;
            case 'getRelatedRecords':
                return $this->populateResponse(
                    $this->formGetRecordsResponseArray($response, $recordType));
                break;
            case 'searchRecords':
                return $this->populateResponse(
                    $this->formGetRecordsResponseArray($response, $recordType));
                break;
            case 'getUsers':
                return $this->populateResponse(
                    $this->formGetUsersResponseArray($response));
                break;
            case 'getFields':
                $this->module = new ZohoModule($recordType);
                return $this->populateResponse(
                    $this->formGetFiledsResponseArray($response, $recordType));
                break;
            case 'getModules':
                return $this->populateResponse(
                    $this->formGetModuleResponseArray($response));
                break;
            default:
                throw new \Exception('Invalid operation :'.$operation);
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

    private function formGetModuleResponseArray(array $response)
    {
        $response['message'] = 'Record(s) fetched successfully';
        $response['uri'] = $response['response']['uri'];
        $modules = [];
        foreach($response['response']['result']['row'] as $module){
            $modules[$module['no']] = [
                'id' => $module['id'],
                'singular' => $module['sl'],
                'plural' => $module['pl'],
                'devName' => isset($module['content']) ? $module['content'] : $module['pl'],
                'gt'    => $module['gt']
            ];
        }
        $response['recordDetails'] = $modules;
        unset($response['response']);
        $response['response'] = $response;
        return $response;
    }

    private function formGetRecordsResponseArray(array $response, $recordType)
    {
        $response['message'] = 'Record(s) fetched successfully';
        $response['uri'] = $response['response']['uri'];
        if (isset($response['response']['result'][$recordType]['row']['FL'])) {
            // single Record
            foreach ($response['response']['result'][$recordType] as $row) {
                array_walk($row['FL'], function ($details) use (&$response, $row) {
                    $response['recordDetails'][$details['val']] = $details['content'];
                });
            }
        }else {
            // multiple records
            foreach ($response['response']['result'][$recordType]['row'] as $record) {
                array_walk($record['FL'], function ($details) use (&$response, $record) {
                    $response['recordDetails'][$record['no']][$details['val']] = $details['content'];
                });
            }
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


    private function formGetFiledsResponseArray(array $response, $recordType)
    {
        $response['message'] = 'Fields fetched successfully';
        $response['uri'] = null;
        $response['recordDetails'] = null;
        if (!isset($response[$recordType]['section'])) {
            throw new \Exception('Error fetching field info: Section');
        }
        foreach ($response[$recordType]['section'] as $index => $responseSection){
            $section = (new ZohoSection)->setModule($recordType)
                ->setName($responseSection['dv'])->setLabel($responseSection['name'])
                ->setIndex($index);
            if (isset($responseSection['FL'])){
                if (isset($responseSection['FL']['dv'])){ // that is a single field section
                    $field = new ZohoField($responseSection['FL']);
                    $section->addField($field);
                    $this->module->addField($field);
                    $response['recordDetails'][] = $field->getFieldInfo();
                }else {
                    foreach ($responseSection['FL'] as $fieldInfo) {
                        $field = new ZohoField($fieldInfo);
                        $section->addField($field);
                        $this->module->addField($field);
                        $response['recordDetails'][] = $field->getFieldInfo();
                    }
                }
            }
            $this->module->addSection($section);
        }
        $response['response'] = $response;
        return $response;

    }

    /**
     * General operation response for version 1 and 2  (insert, update, delete ... etc)
     * Not suitable for Get Records of any kind
     *
     * @param array $response
     * @return array
     */
    private function formResponseArray(array $response)
    {
        $response['message'] = $response['response']['result']['message'];
        $response['uri'] = $response['response']['uri'];
        $responseRecordDetails = $response['response']['result']['recorddetail'];
        if (isset($responseRecordDetails['FL'])) {
            //SINGLE
            array_walk($responseRecordDetails['FL'],
                function ($details) use (&$response) {
                    $response['recordDetails'][$details['val']] = $details['content'];
                });
        }else {
            //Multiple
            foreach ($responseRecordDetails as $index => $record){
                array_walk($record['FL'],
                    function ($details) use (&$response, $index) {
                        $response['recordDetails'][$index][$details['val']] = $details['content'];
                    });
            }
        }
        unset($response['response']);
        $response['response'] = $response;
        return $response;
    }

    /**
     * Delete Record operations response
     *
     * @param array $response
     * @return array
     */
    private function formDeleteResponseArray(array $response)
    {
        $response['message'] = $response['response']['result']['message'];
        $response['uri'] = $response['response']['uri'];
        unset($response['response']);
        $response['response'] = $response;
        return $response;
    }

    /**
     * Delete Attachment operations response
     * Errors will be caught with checkForErrors()
     *
     * @param array $response
     * @return array
     */
    private function formDeleteFileResponseArray(array $response)
    {
        $response['message'] = $response['response']['success']['message'];
        $response['uri'] = $response['response']['uri'];
        unset($response['response']);
        $response['response'] = $response;
        return $response;
    }

    /**
     * This is the Version 4 parse of the insert response
     *
     * @param array $response
     * @return array
     * @throws \Exception
     */
    private function formV4ResponseArray(array $response)
    {
        $responseMessage = 'Request was successful';
        $response['uri'] = $response['response']['uri'];
        if (isset($response['response']['result']['row'])) {
            if (isset($response['response']['result']['row']['no'])){
                $response = $this->parseV4Row($response, $response['response']['result']['row']);
            }else {
                foreach ($response['response']['result']['row'] as $row) {
                    $response = $this->parseV4Row($response, $row);
                }
            }
        }else {
            throw new \Exception('There has been errors in your request. Check ZohoResponse->success() for details', '8001');
        }
        if (!self::$result) {
            $responseMessage = 'There has been errors processing your request';
        }
        $response['message'] = isset($response['response']['result']['message']) ?
            $response['response']['result']['message'] : $responseMessage;
        unset($response['response']);
        $response['response'] = $response;
        return $response;
    }

//    private function formUpdateResponseArray(array $response)
//    {
//        $responseMessage = 'All records updated successfully';
//        $response['uri'] = $response['response']['uri'];
//        if (isset($response['response']['result']['recorddetail'])) {
//            $responseRecordDetails = $response['response']['result']['recorddetail'];
//            if (isset($responseRecordDetails['FL'])) {
//                array_walk($responseRecordDetails['FL'],
//                    function ($details) use (&$response) {
//                        $response['recordDetails'][$details['val']] = $details['content'];
//                    });
//            }
//            else {
//                foreach ($responseRecordDetails as $index => $record){
//                    array_walk($record['FL'],
//                        function ($details) use (&$response, $index) {
//                            $response['recordDetails'][$index][$details['val']] = $details['content'];
//                        }
//                    );
//                }
//            }
//        }else {
//            // multiple update record result
//            if (isset($response['response']['result']['row'])) {
//                foreach ($response['response']['result']['row'] as $row){
//                    $response = $this->parseV4Row($response, $row);
//                }
//                if (!self::$result) {
//                    $responseMessage = 'There has been errors while updating';
//                }
//            }else {
//                throw new \Exception('There has been errors in your request. Check ZohoResponse->success() for details', '8001');
//            }
//
//        }
//        $response['message'] = isset($response['response']['result']['message']) ?
//            $response['response']['result']['message'] : $responseMessage;
//        unset($response['response']);
//        $response['response'] = $response;
//        return $response;
//    }
    /**
     * Api V4 response parses for single and multiple records
     *
     * @param $response
     * @param $row
     * @return mixed
     */
    private function parseV4Row ($response, $row)
    {
        if (isset($row['error'])){
            $response['recordDetails'][$row['no']] = [
                'result' => 'error',
                'code' => $row['error']['code'],
                'details' => $row['error']['details']
            ];
            self::$result = false;
        }else {
            $response['recordDetails'][$row['no']] = [
                'result' => 'success',
                'code' => $row['success']['code'],
                'message' => ZohoErrors::$checkDuplicateCodes[$row['success']['code']],
            ];
            array_walk($row['success']['details']['FL'],
                function ($details) use (&$response, $row) {
                    $response['recordDetails'][$row['no']]['details'][$details['val']] = $details['content'];
                });
        }
        return $response;
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