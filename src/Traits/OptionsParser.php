<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 12/12/2016
 * Time: 10:22 AM
 */

namespace CatchZohoMapper\Traits;


use CatchZohoMapper\ZohoOperationParams;
use CatchZohoMapper\ZohoServiceProvider as Zoho;

trait OptionsParser
{
    public function setOpts (ZohoOperationParams $options, array $opts)
    {
        if (array_key_exists('duplicateCheck', $opts)) {
            if (!$opts['duplicateCheck']){
                $options->setDuplicateCheck(null);
            }else {
                if (strtolower($opts['duplicateCheck']) == 'error'){
                    $options->setDuplicateCheck(1);
                }elseif (strtolower($opts['duplicateCheck']) == 'update'){
                    $options->setDuplicateCheck(2);
                }
            }
        }
        if (array_key_exists('version', $opts)) {
            $options->setVersion($opts['version']);
        }
        if (array_key_exists('wfTrigger', $opts)) {
            $options->setWfTrigger($opts['wfTrigger'] ? 'true' : 'false');
        }
        if (array_key_exists('isApproval', $opts)) {
            $options->setIsApproval($opts['isApproval'] ? 'true' : 'false');
        }
        if (array_key_exists('newFormat', $opts)) {
            $options->setNewFormat($opts['newFormat']);
        }
        if (array_key_exists('includeNull', $opts) || in_array('includeNull', $opts)) {
            $options->setNewFormat(2);
        }
        if (array_key_exists('fromIndex', $opts)){
            $options->setFromIndex($opts['fromIndex']);
        }
        if (array_key_exists('toIndex', $opts)){
            $fromIndex = array_key_exists('fromIndex', $opts) ? intval($opts['fromIndex']) : 1;
            if ( intval($opts['toIndex']) - $fromIndex > Zoho::maxGetRecords()){
                throw new \Exception('API allows a maximum of ' .Zoho::maxGetRecords(). ' records to be fetched per call');
            }
            $options->setToIndex($opts['toIndex']);
        }
        if (array_key_exists('selectColumns', $opts)){
            $options = $this->setSelectColumns($options, $opts['selectColumns']);
        }
        if (array_key_exists('sortColumnString', $opts)){
            $options->setSortColumnString($opts['sortColumnString']);
            if (array_key_exists('sortOrderString', $opts)){
                if (strtolower($opts['sortOrderString']) != 'asc' && strtolower($opts['sortOrderString'] != 'desc')){
                    throw new \Exception('Invalid sort order value. Allowed values : asc , desc');
                }
                $options->setSortOrderString(strtolower($opts['sortOrderString']));
            }
        }
        if (array_key_exists('lastModifiedTime', $opts)){
            $options->setLastModifiedTime(date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $opts['lastModifiedTime']))));
        }
        return $options;
    }

    private function setSelectColumns(ZohoOperationParams $params, $selectColumns)
    {
        if (is_array($selectColumns)){
            $columns = $this->recordType.'('.implode(',', $selectColumns).')';
        }else {
            $columns = $this->recordType."($selectColumns)";
        }
        $params->setSelectColumns($columns);
        return $params;
    }
}