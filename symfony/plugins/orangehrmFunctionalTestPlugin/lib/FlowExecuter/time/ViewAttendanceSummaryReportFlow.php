<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewAttendanceSummaryReport
 *
 * @author Arsh
 */
class ViewAttendanceSummaryReportFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $projectReportPageObject;

    public function __construct($selenium) {
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->projectReportPageObject = menu::goToAttendanceSummaryReport($this->selenium);
        $this->projectReportPageObject->viewAttendanceReport($this->dataArray['employeeName'], $this->dataArray['jobTitle'], $this->dataArray['subUnit'], $this->dataArray['employeeStatus'], $this->dataArray['fromDate'], $this->dataArray['toDate']);

        if ($verify) {
            return $this->verify();
        }
        return true;
    }

    public function verify() {
        return true;
    }

}