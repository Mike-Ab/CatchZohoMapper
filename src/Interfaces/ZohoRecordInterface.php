<?php

namespace CatchZohoMapper\Interfaces;


interface ZohoRecordInterface
{
    public function add();
    public function fetch();
    public function update();
    public function save();
    public function delete();
    public function value($key);
    public function has($key);
    public function hasValue($key);
    public function all();
    public function toArray();
    public function uploadFile();
    public function downloadFiles();
}
