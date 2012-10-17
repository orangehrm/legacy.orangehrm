<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeaveSummaryPageObject
 *
 * @author madusani
 */
class ViewLeaveSummaryPageObject extends Component {

    public $cmbLeavePeriod = "//select[@id='leaveSummary_cmbLeavePeriod']";
    public $txtEmployeeName = "//input[@id='leaveSummary_txtEmpName_empName']";
    public $cmbLocation = "//select[@id='leaveSummary_cmbLocation']";
    public $cmbRecordsPerPage = "//select[@id='leaveSummary_cmbRecordsCount']";
    public $checkTerminated = "//input[@id='leaveSummary_cmbWithTerminated']";
    public $cmbLeaveType = "//select[@id='leaveSummary_cmbLeaveType']";
    public $cmbJobTitle = "//select[@id='leaveSummary_cmbJobTitle']";
    public $cmbSubUnit = "//select[@id='leaveSummary_cmbSubDivision']";
    public $btnSearch = "//input[@id='btnSearch']";
    public $btnReset = "//input[@id='btnReset']";
    public $list;

    public function __construct($selenium) {
        parent::__construct($selenium, "View Leave Summary");
        $this->list = new LeaveSummaryList($selenium, "//form[@id='frmLeaveSummarySearch']", true);
    }

    public function viewLeaveSummaryRecords($leavePeriod, $employeeName=null, $location=null, $recordsPerPage=null, $terminated=false, $leaveType=false, $jobTitle=false, $subUnit=false) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->select($this->cmbLeavePeriod, $leavePeriod);
        if ($employeeName)
            $this->selenium->type($this->txtEmployeeName, $employeeName);
        if ($location)
            $this->selenium->select($this->cmbLocation, $location);
        if ($recordsPerPage)
            $this->selenium->select($this->cmbRecordsPerPage, $recordsPerPage);
        if ($terminated)
            $this->selenium->click($this->checkTerminated);
        if ($leaveType)
            $this->selenium->select($this->cmbLeaveType, $leaveType);
        if ($jobTitle)
            $this->selenium->select($this->cmbJobTitle, $jobTitle);
        if ($subUnit)
            $this->selenium->select($this->cmbSubUnit, $subUnit);
        $this->selenium->clickAndWait($this->btnSearch);
    }

}