<?php

namespace CatchZohoMapper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Description of ZohoServiceProvider
 *
 * @author mohammada
 */
class ZohoServiceProvider 
{
    /**
     * Generate the API endpoing needed for the certain API function
     * 
     * @param string $module
     * @param string $responseType
     * @return string the URL for the API call to be made
     */
    public static function generateURL($module, $responseType = 'json')
    {
        if (debug_backtrace()[2]['function'] == "getModules") {
            $module = 'Info';
        }
        return 'https://crm.zoho.com/crm/private/'
            .$responseType.'/'
            .$module.'/'
            .debug_backtrace()[2]['function'];
    }
    
    /**
     * Generate the XML from accociated array with the keys/values 
     * 
     * @param array $data
     * @param string $recordType
     * @return string
     */
    public static function generateXML(array $data, $recordType)
    {
        $xml = '';
        $index = 0;
        $xml .= "<$recordType>";
        if (isset ($data[0]) && is_array($data[0])) {
            foreach ($data as $row) {
                $xml .= "<row no='" . ++$index . "'>";
                foreach ($row as $key => $val) {
                    $xml .= "<FL val='$key'><![CDATA[str_replace('&', 'and', $val)]]></FL>";
                }
                $xml .= "</row>";
            }
        }else {
            $xml .= "<row no='1'>";
            foreach ($data as $key => $val) {
                $xml .= "<FL val='$key'><![CDATA[str_replace('&', 'and', $val)]]></FL>";
            }
            $xml .= "</row>";
        }
        $xml .= "</$recordType>";
        return $xml;
    }

    public static function allowedUserTypes()
    {
        return [
            'AllUsers', 'ActiveUsers', 'DeactiveUsers',
            'AdminUsers', 'ActiveConfirmedAdmins'
        ];
    }

    public static function maxGetRecords()
    {
        return 200;
    }

    public static function formSearchCriteria(array $searchOptions)
    {
        $tempArray = [];
        foreach ($searchOptions as $key => $option){
            if (is_array($option)){
                $tempArray[] = self::formAndCriteria($option);
            }else {
                $tempArray[] = '('.$key.':'.$option.')';
            }
        }
        return (implode('OR',$tempArray));
    }

    private static function formAndCriteria(array $array)
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            if (count($array) == 1) {
                if (is_array($value)){
                    $newArray[] = self::formAndCriteria($value);
                }else {
                    return '(' . $key . ':' . $value . ')';
                }
            }else {
                if (is_array($value)){
                    $newArray[] = self::formAndCriteria($value);
                }else {
                    $newArray[] = '(' . $key . ':' . $value . ')';
                }
            }
        }
        return '('.implode('AND', $newArray).')';
    }

    public static function execute(ZohoOperationParams $params, $method = 'POST')
    {
        var_dump($params::getParams());
        try {
            $http = new Client(['verify' => false]);
            $attempt = $http->request('POST', self::generateURL($params::getRecordType()), [
                'form_params' => $params::getParams()
            ]);
            return ($attempt->getBody());
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public static function sendFile(ZohoOperationParams $params)
    {
        try {
            $http = new Client(['verify' => false]);
            $attempt = $http->request('POST', self::generateURL($params::getRecordType()), [
                'multipart' => [
                    [
                        'name' => 'id',
                        'contents' => $params::getId()
                    ],
                    [
                        'name' => 'authtoken',
                        'contents' => $params::getAuthtoken()
                    ],
                    [
                        'name' => 'scope',
                        'contents' => $params::getScope()
                    ],
                    [
                        'Content-type' => 'multipart/form-data',
                        'name' => 'content',
                        'contents' => fopen($params::getContent(), 'r')
                    ]
                ]
            ]);
            return ($attempt->getBody());
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}
