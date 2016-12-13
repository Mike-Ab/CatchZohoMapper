<?php
namespace CatchZohoMapper\Record;


trait Record
{
    private $module;
    private $parent = null;
    private $properties = ['id'];
    public $id;

    /**
     * Set a value for a property or create the property if it doesn't exist
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value)
    {
        if (isset($this->{$key})){
            $this->{$key} = $value;
        }else {
            $this->createProperty($key, $value);
            array_push($this->properties, $key);
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
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param mixed $module
     * @return $this
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @param null $parent
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
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
    public function toArray()
    {
        $params = [];
        foreach ( $this->properties as $property){
            if (isset($this->{$property})) {
                $params[$property] = $this->get($property);
            }
        }
        return $params;
    }

    /**
     * Check if a key or property exists
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->{$key});
    }

    public function hasValue($key)
    {
        if ($this->has($key)){
            return !empty($this->get($key));
        }
    }

    /**
     * @param array $values
     * @return $this
     */
    protected function populate(array $values)
    {
        foreach ($values as $key => $value){
            $this->set($key, $value);
        }
        return $this;
    }

}