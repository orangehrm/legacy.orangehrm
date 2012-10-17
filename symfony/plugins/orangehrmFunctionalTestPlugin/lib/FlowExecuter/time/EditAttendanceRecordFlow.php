<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EditAttendanceRecordFlow
 *
 * @author Arsh
 */
class EditAttendanceRecordFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $EditAttendanceRecordPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $viewAttendance = new ViewAttendanceRecord($this->selenium);

        $this->EditAttendanceRecordPageObject = $viewAttendance->clickEditAttendanceRecord();


        $this->EditAttendanceRecordPageObject->editEmployeeAttendanceRecords($this->dataArray);


        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
  
       return $this->EditAttendanceRecordPageObject->verifyData($this->dataArray[0]);

    }

}