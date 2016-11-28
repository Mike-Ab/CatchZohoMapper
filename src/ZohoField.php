<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 11/28/2016
 * Time: 12:09 PM
 */

namespace CatchZohoMapper;

use \CatchZohoMapper\Traits\Field;

class ZohoField
{
    use Field;

    public function __construct(array $fieldInfo = [])
    {
        if (count($fieldInfo) > 0){
            return $this->initField($fieldInfo);
        }
    }

    private function initField(array $fieldInfo)
    {
        $this->setType($fieldInfo['type'])
            ->setCustom($fieldInfo['customfield'])
            ->setRequired($fieldInfo['req'])
            ->setLabel($fieldInfo['label'])
            ->setName($fieldInfo['dv'])
            ->setReadOnly($fieldInfo['customfield'])
            ->setMaxLength($fieldInfo['maxlength']);
        if (strtolower($this->type) == 'boolean' ){
            $this->setEnabled($fieldInfo['enabled']);
        }
        if (strtolower($this->type) == 'pick list' ){
            $this->setValueOptions($fieldInfo['val']);
        }
        return $this;
    }


}