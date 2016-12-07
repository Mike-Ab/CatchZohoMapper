<?php

namespace CatchZohoMapper\Interfaces;


interface ZohoRecordInterface
{
    public function insert();//
    public function fetch();//
    public function save(); //
    public function delete(); //
    public function get($key); //
    public function set($key, $value); //
    public function has($key); //
    public function hasValue($key); //
    public function describe(); //
    public function toArray(); //
    public function attachFile($filePath, $url); //
    public function getAttachments(); //
}
