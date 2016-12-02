<?php
namespace CatchZohoMapper\Traits;

use CatchZohoMapper\ZohoSection;

trait Field
{
    private  $module;
    private  $section = null;
    private  $Required;
    private  $label;
    private  $name;
    private  $maxLength;
    private  $readOnly;
    private  $custom;
    private  $type;
    private  $enabled = null;
    private  $lookupModule = null;
    private  $valueOptions = null;

    /**
     * @param $section
     * @return $this
     */
    public function setSection(ZohoSection $section)
    {
        $this->section = $section;
        return $this;
    }

    /**
     * @param $Required
     * @return $this
     */
    public function setRequired($Required)
    {
        $this->Required = $Required;
        return $this;
    }

    /**
     * @param $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $maxLength
     * @return $this
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @param $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @param $lookupModule
     * @return $this
     */
    public function setLookupModule($lookupModule)
    {
        $this->lookupModule = $lookupModule;
        return $this;
    }

    /**
     * @param $custom
     * @return $this
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
        return $this;
    }

    /**
     * @param $readOnly
     * @return $this
     */
    public function setReadOnly($readOnly)
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param $valOptions
     * @return $this
     */
    public function setValueOptions($valOptions)
    {
        $this->valueOptions = $valOptions;
        return $this;
    }

    /**
     * @param null $enabled
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @return null
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return null
     */
    public function getLookupModule()
    {
        return $this->lookupModule;
    }

    /**
     * @return mixed
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }

    /**
     * @return mixed
     */
    public function getReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * @return mixed
     */
    public function getRequired()
    {
        return $this->Required;
    }

    /**
     * @return null
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return null
     */
    public function getValueOptions()
    {
        return $this->valueOptions;
    }

    /**
     * Get the field info
     *
     * @return array
     */
    public function getFieldInfo()
    {
        $params = [];
        foreach (get_class_vars(self::class) as $name => $value){
            if (isset($this->$name)) {
                $params[$name] = $this->$name;
            }
        }
        return $params;
    }


}