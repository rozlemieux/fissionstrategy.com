<?php

include_once '/Users/cindymottershead/WebRoot/FissionStrategy/fissionstrategy/application/modules/CMS/controllers/create_db/googlespreadsheet_api.php';

//$spreadsheets = list_spreadsheets();
//echo print_r($spreadsheets,1);
//exit;

//$s = cache_spreadsheet(0, "0ArRq4JNWRs-sdFVLbnd2VEh5WWNTa01DM0VXcnlhZnc");
//echo print_r($s,1);
//exit;

$spreadsheets = list_spreadsheets();

foreach ($spreadsheets as $i => $sheet) {
    echo print_r($sheet,1) . "\r\n";
    $s = cache_spreadsheet($i, $sheet['key']);
}

function list_spreadsheets() {
    $spreadsheet = array();
    
    $gspreadsheet = new GoogleSpreadsheetAPI();
    $gspreadsheet->init();
    $feed = $gspreadsheet->promptForSpreadsheet();
    $i = 0;
    foreach ($feed->entries as $entry) { 
        $spreadsheet[$i]['name'] = $entry->title->text;
        $spreadsheet[$i]['key'] = substr($entry->link[1]->href, strpos($entry->link[1]->href, '?key=') + 5);
        $i++;
    } 
    return $spreadsheet;
}

function get_list_spreadsheet($i, $key) {

    $gspreadsheet = new GoogleSpreadsheetAPI();
    $gspreadsheet->setCurrKey($key);
    $gspreadsheet->init();
    $gspreadsheet->listAllWorksheets();
    $featured = $gspreadsheet->getWorksheets();

    file_put_contents("Blogger{$i}_worksheet.txt", serialize($featured));
    return $featured;
}

function cache_spreadsheet($i, $key) {

    $gspreadsheet = new GoogleSpreadsheetAPI();
    $gspreadsheet->init();
    $gspreadsheet->setCurrKey($key);
    $gspreadsheet->listAllWorksheets();
    $featured = $gspreadsheet->getWorksheets();

    file_put_contents("Blogger{$i}_worksheet.txt", serialize($featured));
    return $featured;
}


?>