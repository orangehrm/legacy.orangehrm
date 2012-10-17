<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EditTimeSheet
 *
 * @author Faris
 */
class EditTimeSheetFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $editTimeSheetPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->editTimeSheetPageObject = new EmployeeTimeSheet($this->selenium);
        $addRecords = $this->editTimeSheetPageObject->clickEditTimeSheet();
        $addRecords->addTimeSheetRecord($this->dataArray);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        return TRUE;
    }

}