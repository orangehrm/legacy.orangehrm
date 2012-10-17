<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SaveTimeSheetFlow
 *
 * @author Faris
 */
class SaveTimeSheetFlow extends Flow {

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

        $this->saveTimeSheetObject = new TimeSheetEditView($this->selenium);
        $this->saveTimeSheetObject->clickSave();

        if ($verify) {
            return $this->verify();
        }
        return true;
    }
//chages
    public function verify() {

        if ($this->saveTimeSheetObject->verifyMessage("Successfully Saved")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}