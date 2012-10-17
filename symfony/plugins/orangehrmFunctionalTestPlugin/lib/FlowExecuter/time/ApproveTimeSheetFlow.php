<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApproveTimeSheetFlow
 *
 * @author Faris
 */
class ApproveTimeSheetFlow extends Flow {

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
        $this->saveTimeSheetObject->approveTimeSheet();

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        if ($this->saveTimeSheetObject->verifyStatus("Approved")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}