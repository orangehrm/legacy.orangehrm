<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddAttendanceRecordFlow
 *
 * @author Arsh
 */
class AddAttendanceRecordFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $addAttendancePageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->addAttendancePageObject = new ViewAttendanceRecord($this->selenium);
        sleep(10);
        $this->addAttendancePageObject->clickAddAttendanceRecord();


        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        return true;
    }

}