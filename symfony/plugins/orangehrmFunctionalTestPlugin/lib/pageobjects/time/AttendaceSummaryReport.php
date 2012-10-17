<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AttendaceSummaryReport
 *
 * @author Faris
 */
class AttendaceSummaryReport extends Page {

    public $txtemployeeName;
    public $jobTitle;
    public $subUnit;
    public $employmentStatus;
    public $dateFrom;
    public $dateTo;
    public $btnView;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->txtemployeeName = "employee_name";
        $this->jobTitle = "attendanceTotalSummary_jobTitle";
        $this->subUnit = "attendanceTotalSummary_subUnit";
        $this->employmentStatus = "attendanceTotalSummary_employeeStatus";
        $this->dateFrom = "from_date";
        $this->dateTo = "to_date";
        $this->btnView = "viewbutton";
    }

    public function viewAttendanceReport($employeeName, $jobTitle=null, $subUnit=null, $employmentStatus=null, $dateFrom=null, $dateTo=null) {

        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtemployeeName, $employeeName);
        if ($jobTitle != null) {
            $this->selenium->select($this->jobTitle, $jobTitle);
        }

        if ($subUnit != null) {
            $this->selenium->select($this->subUnit, $subUnit);
        }

        if ($employmentStatus != null) {
            $this->selenium->select($this->employmentStatus, $employmentStatus);
        }

        if ($dateFrom != null) {
            $this->selenium->type($this->dateFrom, $dateFrom);
        }

        if ($dateTo != null) {
            $this->selenium->select($this->dateTo, $dateTo);
        }

        $this->selenium->click($this->btnView);
    }

}