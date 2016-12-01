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

    /**
     * @var null
     */
    protected $module = null;


    public function __construct($responseObject = null)
    {

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

    public function handleResponse($responseObject, $recordType = false)
    {
        $operation = debug_backtrace()[1]['function'];
        $response = (json_decode($responseObject, true));
        self::checkForErrors($response);
        switch ($operation) {
            case 'insertRecords':
                return $this->populateResponse($this->formResponseArray($response));
                break;
            case 'updateRecords':
                return $this->populateResponse($this->formResponseArray($response));
                break;
            case 'uploadFile':
                return $this->populateResponse(
                    $this->formResponseArray($response));
                break;
            case 'getRecordById':
                return $this->populateResponse(
                    $this->formGetRecordsResponseArray($response, $recordType));
                break;
            case 'getRecords':
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
                    $response['recordDetails'][$row['no']][$details['val']] = $details['content'];
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

    private function formResponseArray(array $response)
    {
        $response['message'] = $response['response']['result']['message'];
        $response['uri'] = $response['response']['uri'];
        $responseRecordDetails = $response['response']['result']['recorddetail'];
        if (isset($responseRecordDetails['FL'])) {
//            echo 'SINGLE ';
            array_walk($responseRecordDetails['FL'],
                function ($details) use (&$response) {
                    $response['recordDetails'][$details['val']] = $details['content'];
                });
        }else {
//            echo 'Multiple ';
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