<?php
use CatchZohoMapper\ZohoCrm;

require ('vendor/autoload.php');
try {
$zoho = new ZohoCrm('ff5196138d9b9112b7fe675a9c6025d0', 'Leads');
$lead = [
    'First Name' => 'testttt',
    'Last Name' => 'testttt',
    'Email' => 'it+'.time().'@gqaustralia.com.au'
];


    echo '<pre>';

    var_dump(
    $zoho->select(['First Name', 'Email'])
        ->from('Leads')
        ->where(['First Name' => 'testtt'])
        ->limit(10)
        ->get()
    );

} catch (Exception $e)
{
    var_dump($e->getMessage());
}