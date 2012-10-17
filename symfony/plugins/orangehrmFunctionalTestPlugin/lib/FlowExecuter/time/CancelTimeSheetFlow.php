<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CancelTimeSheetFlow
 *
 * @author Faris
 */
class CancelTimeSheetFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $cancelTimeSheetObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->cancelTimeSheetObject = new TimeSheetEditView($this->selenium);
        $this->cancelTimeSheetObject->clickCancel();

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {

        return true;
    }

}