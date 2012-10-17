<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PunchIn
 *
 * @author Faris
 */
class PunchIn extends Flow {

    public $dataArray;
    public $selenium;
    public $punchInPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->punchInPageObject = menu::goToPunchInPunchOut($this->selenium);
        $this->punchInPageObject->punchIn($this->dataArray['attendanceDate'], $this->dataArray['attendanceTime'], $this->dataArray['attendanceTimezone'], $this->dataArray['attendanceNote']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {

        $data = $this->dataArray['attendanceDate']. " " . $this->dataArray['attendanceTime'] . ":00";
        //echo $data;
        if($this->punchInPageObject->verifyPunchIn($data, null))
        return TRUE;
        else
        return FALSE;
    }

}