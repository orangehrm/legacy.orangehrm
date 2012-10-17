<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SelectTimeSheetFlow
 *
 * @author Faris
 */
class SelectTimeSheetFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $saveTimeSheetObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->saveTimeSheetObject = new EmployeeTimeSheet($this->selenium);
        $this->saveTimeSheetObject->selectWeekForTimeSheet($this->dataArray['dateRange']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        return true;
    }

}