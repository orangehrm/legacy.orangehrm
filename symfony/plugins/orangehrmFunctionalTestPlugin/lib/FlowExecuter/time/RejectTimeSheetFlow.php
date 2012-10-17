<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RejectTimeSheetFlow
 *
 * @author Arsh
 */
class RejectTimeSheetFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $rejectTimeSheetObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->rejectTimeSheetObject = new EmployeeTimeSheet($this->selenium);
        $this->rejectTimeSheetObject->rejectTimeSheet();

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        if ($this->rejectTimeSheetObject->verifyStatus("Rejected")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}