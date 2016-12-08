<?php
/**
 * Created by PhpStorm.
 * User: mohammada
 * Date: 11/28/2016
 * Time: 9:15 AM
 */
use CatchZohoMapper\ZohoMapper;

require ('vendor/autoload.php');
try {
$zoho = new ZohoMapper('ff5196138d9b9112b7fe675a9c6025d0', 'Leads');
$lead = [
    'First Name' => 'testttt',
    'Last Name' => 'testttt',
    'Email' => 'it+'.time().'@gqaustralia.com.au'
];
//$add = $zoho->insertRecords($lead)->getRecordDetails();

    echo '<pre>';
//    foreach($zoho->getFields(false)->getModule()->getFields() as $field){
//        var_dump($field->getFieldInfo());
//    }
var_dump($zoho->uploadFile('696292000041727818', __DIR__.DIRECTORY_SEPARATOR.'resized.jpg' )->getRecordDetails());
    $searchOptions = [
        'fromIndex' => 199,
        'toIndex' => 250,
        'sortColumnString' => 'First Name',
        'sortOrderString' => 'asc',
        'selectColumns' => [
            'First Name',
            'Last Name',
            'Email',
            'Mobile'
            ],
        'includeNull' => false,
        'lastModifiedTime' => '25/11/2016'
    ];
$ids = ['696292000043454178', '696292000043448211', '696292000043435939'];
$id = '696292000043454178';
$searchCriteria = [ // [[1] OR [[2] AND[3]]]
    'Email' => 'Kylie.Pomerenke@bne.centacare.net.au',
    'First Name' => 'mike',
    [
        'Last Name' => 'testtt',
        'Lead Status' => 'Junk Lead',
//        'test' => [ // this is not allowed
//            'Active in the system' => 'Open'
//        ],
    ],
    [
        'First Name' => 'testttt'
    ]

];

    $searchOptions2 = [
        'fromIndex' => 0,
        'toIndex' => 150,
        'selectColumns' => [
            'First Name',
            'Last Name',
            'Email',
            'Mobile'
        ],
        'includeNull' => false,
        'lastModifiedTime' => '25/11/2013'
    ];
//$pro = (new \CatchZohoMapper\ZohoServiceProvider);
//var_dump($pro::formSearchCriteria($searchCriteria));
//var_dump($zoho->searchRecords($searchCriteria, $searchOptions2)->getRecordDetails());
//var_dump($zoho->getMyRecords($searchOptions2)->getRecordDetails());
//var_dump($zoho->downloadFile('696292000043685003'));
$updateIds = ['696292000043529806', '696292000023479146', '696292000041727818'];
$updates = [
    'First Name' => 'Test Only',
    'Lead Status' => 'Junk Lead'
];
$idArray = ['696292000043529806'];
$id = '696292000043608273';
$updatesFull = [
    '696292000043608273' => [
        'First Name' => 'New',
        'Lead Status' => 'Junk Lead'
    ],
    '696292000023479146' => [
        'First Name' => 'New Also',
        'Lead Status' => 'Not Proceeding'
    ],
];
$insert = [
    [
        'First Name' => 'Guzzle1',
        'Last Name' => 'Testttt',
        'Email' => 'it+'.time().'@gqaustralia.com.au',
        'Mobile' => '040404040404',
    ],
    [
        'First Name' => 'Guzzle2',
        'Last Name' => 'Testttt',
        'Email' => 'it+'.time().'@gqaustralia.com.au',
        'Mobile' => '040404040404',
    ],
    [
        'First Name' => 'Guzzle3',

    ],
    [
        'First Name' => 'Guzzle4',
        'Last Name' => 'Testttt',
        'Email' => 'it+'.time().'@gqaustralia.com.au',
        'Mobile' => '040404040404',
    ],
];
$insertSingle = [
    'First Name' => 'GuzzleSingle',
    'Last Name' => 'Testttt',
    'Email' => 'it+'.time().'@gqaustralia.com.au',
    'Mobile' => '040404040404',
];
//    var_dump($zoho->updateRecords($updatesFull)->getRecordDetails());
//    var_dump($zoho->updateRecords($id, $updates )->getRecordDetails());
//    var_dump($zoho->insertRecords($insert)->getRecordDetails());
//    var_dump($zoho->deleteRecords('198292010011529806')->getResponse());
//    var_dump($zoho->deleteFile('696292000043435139')->getResponse());
//    var_dump($zoho->getFields(false)->getRecordDetails());

    // get all attachment details example
//    $attachment = new ZohoMapper('ff5196138d9b9112b7fe675a9c6025d0', 'Attachments');
//    var_dump($attachment->getRelatedRecords('Leads', '696292000041727818')->getRecordDetails());

} catch (Exception $e)
{
    var_dump($e->getMessage());
}