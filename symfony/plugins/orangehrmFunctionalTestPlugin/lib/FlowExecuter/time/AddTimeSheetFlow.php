<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddTimeSheetFlow
 *
 * @author Faris
 */
class AddTimeSheetFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $addTimeSheetPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->addTimeSheetPageObject = new EmployeeTimeSheet($this->selenium);
        $this->addTimeSheetPageObject->addTimeSheet($this->dataArray['date']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {

        if ($this->addTimeSheetPageObject->verifyStatus("Not Submitted")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}