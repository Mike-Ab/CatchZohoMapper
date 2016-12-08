<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 11/28/2016
 * Time: 10:56 AM
 */

namespace CatchZohoMapper\Module;

use CatchZohoMapper\ZohoMapper;

class ZohoModule
{

    private $moduleName;

    /**
     * @var array
     */
    private $sections = [];
    /**
     * @var array
     */
    private $fields = [];

    /**
     * @var array
     */
    private $mandatory = [];

    public function __construct($moduleName)
    {
        $this->moduleName = $moduleName;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function addField(ZohoField $field)
    {
        $this->fields[] = $field;
        return $this;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function addSection(ZohoSection $section)
    {
        $this->sections[] = $section;
        return $this;
    }

    public function getMandatory()
    {
        return $this->mandatory;
    }

    public function fetchMandatory($authToken)
    {
        return (new ZohoMapper($authToken, $this->moduleName))
            ->getFields(true)
            ->getModule()
            ->getFields();
    }

    public function describe()
    {
        $description = [];
        foreach ($this->sections as $section){
            foreach ($section->getFields() as $field){
                $description[$section->getLabel()][] = $field;
            }
        }
        return $description;
    }

}