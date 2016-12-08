<?php
namespace CatchZohoMapper\Module;
;

trait Section
{
    private $module;
    private $label;
    private $name;
    private $index;
    private $fields = [];

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
     * @param $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function addField(ZohoField $field)
    {
        $this->fields[] = $field;
    }

    /**
     * @param $index
     * @return $this
     */
    public function setIndex($index)
    {
        $this->index = $index;
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
     * @return null
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
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
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the Section info
     *
     * @return array
     */
    public function getInfo()
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