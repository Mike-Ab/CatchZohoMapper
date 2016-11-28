<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 11/28/2016
 * Time: 10:56 AM
 */

namespace CatchZohoMapper;

use CatchZohoMapper\ZohoField;
use CatchZohoMapper\ZohoSection;


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



}