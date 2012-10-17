<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PunchOutSubordinateAttendance
 *
 * @author Faris
 */
class PunchOutSubordinateAttendanceFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $punchOutPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->punchOutPageObject = new AddAttendanceRecord($this->selenium);
        $this->punchOutPageObject->punchOut($this->dataArray['attendanceDate'], $this->dataArray['attendanceTime'], $this->dataArray['attendanceTimezone'], $this->dataArray['attendanceNote']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {

        if($this->punchOutPageObject->verifyPunchOut("Successfully Saved"))
        return TRUE;
        else
        return FALSE;
    }

}