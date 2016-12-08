<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 11/28/2016
 * Time: 12:09 PM
 */

namespace CatchZohoMapper\Module;


class ZohoSection
{
    use Section;

    public function getFields()
    {
        $response = [];
        foreach ($this->getInfo()['fields'] as $field){
            $response[] = $field->getFieldInfo();
        }
        return $response;
    }
}