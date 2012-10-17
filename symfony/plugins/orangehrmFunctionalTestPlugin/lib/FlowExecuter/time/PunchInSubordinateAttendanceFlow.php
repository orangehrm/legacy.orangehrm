<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PunchInSubordinateAttendance
 *
 * @author Faris
 */
class PunchInSubordinateAttendanceFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $punchInPageObject;
    public $viewAttendanceRecord;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->punchInPageObject = new AddAttendanceRecord($this->selenium);
        $this->punchInPageObject->punchIn($this->dataArray['attendanceDate'], $this->dataArray['attendanceTime'], $this->dataArray['attendanceTimezone'], $this->dataArray['attendanceNote']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {

        $this->viewAttendanceRecord = new ViewAttendanceRecord($this->selenium);
        $this->viewAttendanceRecord->clickAddAttendanceRecord();
       
        
        $data = $this->dataArray['attendanceDate']. " " . $this->dataArray['attendanceTime'] . ":00";
        //echo $data;
        if($this->punchInPageObject->verifyPunchIn($data))
        return TRUE;
        else
        return FALSE;
    }

}