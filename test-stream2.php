<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 11/28/2016
 * Time: 9:15 AM
 */
use CatchZohoMapper\Record\ZohoRecord;

require ('vendor/autoload.php');
echo '<pre>';
try {
//    $record = new ZohoRecord('ff5196138d9b9112b7fe675a9c6025d0', 'Leads', '696292000043299334');
    $record = new ZohoRecord('ff5196138d9b9112b7fe675a9c6025d0', 'Leads');
//    var_dump($record->setId('696292000043706155')->fetch()->toArray());
//    $record->set('First Name', 'IT WORKS')->save();
//    var_dump($record->fetch()->toArray());
//    var_dump($record->describe());
//    var_dump($record->fetch());
//    var_dump($record->set('First Name', 'TestRecord2')->set('Last Name', 'testttttt')->set('Email', '1x2x2112@test.com')
//        ->insert(['wfTrigger' => true])->getId());
//    var_dump($record->setId('696292000041727818')->attachFile(__DIR__.DIRECTORY_SEPARATOR.'resized.jpg')->fetch()->toArray());
//    var_dump($record->setId('696292000041727818')->getAttachments());

} catch (Exception $e){
    var_dump($e->getMessage());
}