<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewAttendanceRecord
 *
 * @author Faris
 */
class ViewMyAttendanceRecordFlow extends Flow {

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

        $this->attendanceRecordPageObject = menu::goToPMyAttendanceRecord($this->selenium);
        $this->attendanceRecordPageObject->viewAttendanceRecords($this->dataArray['date']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        //This is just a selected frame
        return true;
    }

}