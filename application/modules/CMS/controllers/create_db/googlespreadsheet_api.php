<?php

ini_set("memory_limit","500M");
set_time_limit(60*10);
ini_set("include_path", ":/users/cindymottershead/WebRoot/GoogleAPI/Zend/library/");

require 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Gdata');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
Zend_Loader::loadClass('Zend_Http_Client');

class GoogleSpreadsheetAPI {

    public $worksheets = null;

    private $currKey = "0Ahhokz-QRqfSdDI4NEJ5MlpNRUdJdVg3cVpuMHZlOGc";
    private $gdClient = null;
    private $currWkshtId = "od6";
    private $listFeed = null;

    public function setCurrKey($key) {
        $this->currKey = $key;
    }

    public function getWorksheets() {
        return $this->worksheets;
    }

    public function init() {
        $this->gdClient = $this->setupClient();
    }

    function setupClient() {
        $email = "fissionbloggers@gmail.com";
        $password = "f1ss1on11";
        $client = Zend_Gdata_ClientLogin::getHttpClient($email, $password,
                  Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME);
        $gdClient = new Zend_Gdata_Spreadsheets($client);
        return $gdClient;
    }
 
    function getWkshtListFeed($gdClient, $ssKey, $wkshtId, $queryString=null) {
        $query = new Zend_Gdata_Spreadsheets_ListQuery();
        $query->setSpreadsheetKey($ssKey);
        $query->setWorksheetId($wkshtId);
        if ($queryString !== null) {
            $query->setSpreadsheetQuery($queryString);
        }
        $listFeed = $gdClient->getListFeed($query);
        return $listFeed;
    }
 
    function printFeed($feed) {
        foreach($feed->entries as $entry) {
            if ($entry instanceof Zend_Gdata_Spreadsheets_CellEntry) {
                $this->worksheets[$this->currWkshtId][$entry->title->text] = $entry->content->text;
            } else if ($entry instanceof Zend_Gdata_Spreadsheets_ListEntry) {
                $this->worksheets[$this->currWkshtId][$entry->title->text] = $entry->content->text;
            } else {
                print $entry->title->text . "\n";
            }
        }
    }

    public function promptForSpreadsheet() {
        $feed = $this->gdClient->getSpreadsheetFeed();
        return $feed;
        print "== Available Spreadsheets ==\n";
        $this->printFeed($feed);
        $input = getInput("\nSelection");
        $currKey = split('/', $feed->entries[$input]->id->text);
        return print_r($currKey,1);
    }

    public function listAllWorksheets() {
        $query = new Zend_Gdata_Spreadsheets_DocumentQuery();
        $query->setSpreadsheetKey($this->currKey);
        $feed = $this->gdClient->getWorksheetFeed($query);
        foreach($feed->entries as $entry) {
            $currWkshtId = split('/', $entry->id->text);
            $this->worksheets[$entry->title->text]['id'] = $currWkshtId[8];
            $this->getWorksheetContentsCell($entry->title->text);
        }
    }

    public function getWorksheetContents($name) {
        $query = new Zend_Gdata_Spreadsheets_ListQuery();

        $this->currWkshtId = $this->worksheets[$name]['id'];
        $query->setSpreadsheetKey($this->currKey);
        $query->setWorksheetId($this->currWkshtId);
        $feed = $this->gdClient->getListFeed($query);
        foreach($feed->entries as $entry) 
            $this->worksheets[$name][$entry->title->text] = $entry->content->text;
    }

    public function getWorksheetContentsCell($name) {
        $query = new Zend_Gdata_Spreadsheets_CellQuery();
        $this->currWkshtId = $this->worksheets[$name]['id'];
        $query->setSpreadsheetKey($this->currKey);
        $query->setWorksheetId($this->currWkshtId);
        $feed = $this->gdClient->getCellFeed($query);
        // save worksheet data
        foreach($feed->entries as $entry) 
            $this->worksheets[$name][$entry->title->text] = $entry->content->text;
    }

    public function listGetAction() {
        $query = new Zend_Gdata_Spreadsheets_ListQuery();
        $query->setSpreadsheetKey($this->currKey);
        $query->setWorksheetId($this->currWkshtId);
        echo "Current ID " . $this->currWkshtId . "\n\b";
        $this->listFeed = $this->gdClient->getListFeed($query);
        $this->printFeed($this->listFeed);
    }

    public function cellsGetAction() {
        $query = new Zend_Gdata_Spreadsheets_CellQuery();
        $query->setSpreadsheetKey($this->currKey);
        $query->setWorksheetId($this->currWkshtId);
        $feed = $this->gdClient->getCellFeed($query);
        $this->printFeed($feed);
    }
  }