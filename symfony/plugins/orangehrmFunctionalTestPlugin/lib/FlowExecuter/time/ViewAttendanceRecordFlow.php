<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewAttendanceRecordFlow
 *
 * @author Arsh
 */
class ViewAttendanceRecordFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $attendanceRecordPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->attendanceRecordPageObject = menu::goToEmployeeAttendanceRecordAsSupervisor($this->selenium);
        $this->attendanceRecordPageObject->viewAttendanceRecords($this->dataArray['date'], $this->dataArray['person']);
        
        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        return true;
    }

}