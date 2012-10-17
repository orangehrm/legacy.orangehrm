<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewProjectReportFlow
 *
 * @author Faris
 */
class ViewProjectReportFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $projectReportPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->projectReportPageObject = menu::goToProjectReports($this->selenium);
        $this->projectReportPageObject->viewProjectReport($this->dataArray[0]['projectName'], $this->dataArray[0]['dateFrom'], $this->dataArray[0]['dateTo'], $this->dataArray[0]['check']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        //print_r($this->dataArray[1]);
        return $this->projectReportPageObject->verifyData($this->dataArray[1]);
        
    }

}