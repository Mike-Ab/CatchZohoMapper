<?php

namespace CatchZohoMapper\Response;


use CatchZohoMapper\Module\ZohoField;
use CatchZohoMapper\Module\ZohoModule;
use CatchZohoMapper\Module\ZohoSection;
use CatchZohoMapper\ZohoErrors;

class ZohoResponseParser
{
    /**
     * @var array
     */
    public $response = [];

    /**
     * @var null
     */
    public $recordType = null;

    /**
     * @var bool
     */
    public $result = true;

    /**
     * @var array
     */
    public $parsedResponse = [];

    /**
     * @var null
     */
    public $module = null;

    /**
     * ZohoResponseParser constructor.
     * @param array $response
     * @param $recordType
     */
    public function __construct(array $response, $recordType)
    {
        $this->recordType = $recordType;
        $this->response = $response;
    }

    /**
     * @return $this
     */
    public function formGetModuleResponseArray()
    {
        $this->populateMessageUri('Record(s) fetched successfully');
        $modules = [];
        foreach($this->response['response']['result']['row'] as $module){
            $modules[$module['no']] = [
                'id' => $module['id'],
                'singular' => $module['sl'],
                'plural' => $module['pl'],
                'devName' => isset($module['content']) ? $module['content'] : $module['pl'],
                'gt'    => $module['gt']
            ];
        }
        $this->parsedResponse['recordDetails'] = $modules;
        return $this;
    }

    /**
     * @return $this
     */
    public function formGetRecordsResponseArray()
    {
        $response = $this->response;
        $this->populateMessageUri('Record(s) fetched successfully');
        if (isset($this->response['response']['result'][$this->recordType]['row']['FL'])) {
            // single Record
            foreach ($this->response['response']['result'][$this->recordType] as $row) {
                array_walk($row['FL'], function ($details) use (&$response, $row) {
                    $response['recordDetails'][$details['val']] = isset($details['content'])? $details['content'] : null;
                });
            }
        }else {
            // multiple records
            foreach ($response['response']['result'][$this->recordType]['row'] as $record) {
                array_walk($record['FL'], function ($details) use (&$response, $record) {
                    $response['recordDetails'][$record['no']][$details['val']] = isset($details['content'])? $details['content'] : null;
                });
            }
        }
        $this->parsedResponse['recordDetails'] = $response['recordDetails'];
        return $this;
    }

    /**
     * @return $this
     */
    public function formGetUsersResponseArray()
    {
        $this->populateMessageUri('Record(s) fetched successfully', $this->response['users']['orgid']);
        $this->parsedResponse['recordDetails'] = $this->response['users']['user'];
        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function formGetFieldsResponseArray()
    {
        $this->module = new ZohoModule($this->recordType);
        $this->populateMessageUri('Fields fetched successfully', 'null');
        if (!isset($this->response[$this->recordType]['section'])) {
            throw new \Exception('Error fetching field info: Section');
        }
        foreach ($this->response[$this->recordType]['section'] as $index => $responseSection){
            $section = (new ZohoSection)->setModule($this->recordType)
                ->setName($responseSection['dv'])->setLabel($responseSection['name'])
                ->setIndex($index);
            if (isset($responseSection['FL'])){
                if (isset($responseSection['FL']['dv'])){ // that is a single field section
                    $field = new ZohoField($responseSection['FL']);
                    $section->addField($field);
                    $this->module->addField($field);
                    $this->parsedResponse['recordDetails'][] = $field->getFieldInfo();
                }else {
                    foreach ($responseSection['FL'] as $fieldInfo) {
                        $field = new ZohoField($fieldInfo);
                        $section->addField($field);
                        $this->module->addField($field);
                        $this->parsedResponse['recordDetails'][] = $field->getFieldInfo();
                    }
                }
            }
            $this->module->addSection($section);
        }
        return $this;
    }

    /**
     * General operation response for version 1 and 2  (insert, update, delete ... etc)
     * Not suitable for Get Records of any kind
     *
     * @return $this
     */
    public function formResponseArray()
    {
        $this->populateMessageUri();
        $responseRecordDetails = $this->response['response']['result']['recorddetail'];
        $response = $this->response;
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
        $this->parsedResponse['recordDetails'] = $response['recordDetails'];
        return $this;
    }

    /**
     * Delete Record operations response
     *
     * @return $this
     */
    public function formDeleteResponseArray()
    {
        $this->populateMessageUri();
        return $this;
    }

    /**
     * Delete Attachment operations response
     * Errors will be caught with checkForErrors()
     *
     * @return $this
     */
    public function formDeleteFileResponseArray()
    {
        $this->populateMessageUri($this->response['response']['success']['message']);
        return $this;
    }

    /**
     * This is the Version 4 parse of the insert response
     *
     * @return $this
     * @throws \Exception
     */
    public function formV4ResponseArray()
    {
        $this->populateMessageUri('Request was successful');
        if (isset($this->response['response']['result']['row'])) {
            if (isset($this->response['response']['result']['row']['no'])){
                $this->parsedResponse = $this->parseV4Row($this->parsedResponse, $this->response['response']['result']['row']);
            }else {
                foreach ($this->response['response']['result']['row'] as $row) {
                    $this->parsedResponse = $this->parseV4Row($this->parsedResponse, $row);
                }
            }
        }else {
            throw new \Exception('There has been errors in your request. Check ZohoResponse->success() for details', '8001');
        }
        if (!$this->result) {
            $responseMessage = 'There has been errors processing your request';
        }

        $this->parsedResponse['message'] = isset($this->response['response']['result']['message']) ?
            $this->response['response']['result']['message'] : $responseMessage;
        return $this;
    }

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
            $this->result = false;
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
     * @param bool|string $message
     * @param bool|string $uri
     */
    private function populateMessageUri($message = false, $uri = false)
    {
        $this->parsedResponse['message'] = $message ?: $this->response['response']['result']['message'];
        $this->parsedResponse['uri'] = $uri ?: $this->response['response']['uri'];
    }
}