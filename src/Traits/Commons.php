<?php
namespace CatchZohoMapper\Traits;


trait Commons
{
    /**
     * Populate the record with properties from the values
     *
     * @param $name
     * @param $value
     */
    public function createProperty($name, $value)
    {
        $this->{$name} = $value;
    }
}
