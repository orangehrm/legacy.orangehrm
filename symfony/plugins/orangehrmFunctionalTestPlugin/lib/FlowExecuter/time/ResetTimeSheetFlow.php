<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ResetTimeSheetFlow
 *
 * @author Arsh
 */
class ResetTimeSheetFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $resetTimeSheetObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->resetTimeSheetObject = new TimeSheetEditView($this->selenium);
        $this->resetTimeSheetObject->clickReset();

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {

        $emplyeeTimeSheet = new EmployeeTimeSheet($this->selenium);
        if ($emplyeeTimeSheet->verifyStatus("Submitted")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}