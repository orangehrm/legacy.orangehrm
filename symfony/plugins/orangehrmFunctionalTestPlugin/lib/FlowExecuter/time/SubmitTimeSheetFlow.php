<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubmitTimeSheetFlow
 *
 * @author Faris
 */
class SubmitTimeSheetFlow extends Flow {

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
        $this->saveTimeSheetObject->clickSubmitTimeSheet();

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        if ($this->saveTimeSheetObject->verifyStatus("Submitted")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}