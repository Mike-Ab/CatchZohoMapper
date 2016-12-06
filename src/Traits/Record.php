<?php
namespace CatchZohoMapper\Traits;

use CatchZohoMapper\ZohoField;

trait Record
{
    private  $module;
    private  $parent = null;
    protected $id;
    protected $fields = [];

    public function __construct($id = false)
    {
        if ($id){
            $this->setId($id);
        }
    }

    public function set($key, $value)
    {
        if (isset($this->{$key})){
            $this->{$key} = $value;
        }else {
            $this->createProperty($key, $value);
        }
        return $this;
    }

    /**
     * Get a value for the key
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->{$key}) ? $this->{$key}: false ;
    }

    /**
     * @param ZohoField $field
     * @return $this
     */
    public function setFields(ZohoField $field)
    {
        $this->fields[] = $field;
        $this->createProperty($field->getName(), $field->getValue());
        return $this;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $module
     */
    public function setModule($module)
    {
        $this->module = $module;
    }

    /**
     * @param null $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @return null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get the field info
     *
     * @return array
     */
    public function getRecordInfo()
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