<?php

namespace CatchZohoMapper;

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
                    $xml .= "<FL val='$key'><![CDATA[$val]]></FL>";
                }
                $xml .= "</row>";
            }
        }else {
            $xml .= "<row no='1'>";
            foreach ($data as $key => $val) {
                $xml .= "<FL val='$key'><![CDATA[$val]]></FL>";
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
}
