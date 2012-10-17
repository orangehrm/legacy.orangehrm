<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewLeaveRecords
 *
 * @author madusani
 */
class ViewLeaveListPageObject extends Component {

    public $txtFromDate = "//input[@id='calFromDate']";
    public $txtToDate = "//input[@id='calToDate']";
    public $checkLeaveStatusAll = "//input[@id='leaveList_chkSearchFilter_checkboxgroup_allcheck']";
    public $checkLeaveStatusRejected = "//input[@id='leaveList_chkSearchFilter_-1']";
    public $checkLeaveStatusCanceled = "//input[@id='leaveList_chkSearchFilter_0']";
    public $checkLeaveStatusPending = "//input[@id='leaveList_chkSearchFilter_1']";
    public $checkLeaveStatusScheduled = "//input[@id='leaveList_chkSearchFilter_2']";
    public $checkLeaveStatusTaken = "//input[@id='leaveList_chkSearchFilter_3']";
    public $txtEmployeeName = "//input[@id='leaveList_txtEmployee_empName']";
    public $cmbSubUnit = "//select[@id='leaveList_cmbSubunit']";
    public $checkTerminated = "//input[@id='leaveList_cmbWithTerminated']";
    public $btnSearch = "//input[@id='btnSearch']";
    public $btnReset = "//input[@id'btnReset']";
    public $list;
    public $config;
    public $calBtnFromDate = "//input[@id='calFromDate_Button']";
    public $calBtnToDate = "//input[@id='calToDate_Button']";

    public function __construct($selenium) {
        parent::__construct($selenium, "Search Leave Records");
        $this->list = new LeaveList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
        $this->config = new TestConfig();
    }

    public function searchLeaveRecords($leaveFromDate, $leaveToDate, $leaveStatusAll=false, $leaveStatusRejected=false, $leaveStatusCanceled=false, $leaveStatusPending=false, $leaveStatusScheduled=false, $leaveStatusTaken=false, $employeeName=null, $subUnit=null, $terminated=false) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->calBtnFromDate, "");
        Calender::selectDateUsingCalendar($this->selenium, $this->calBtnFromDate, $leaveFromDate);
        $this->selenium->type($this->calBtnToDate, "");
        Calender::selectDateUsingCalendar($this->selenium, $this->calBtnToDate, $leaveToDate);
        
        if ($leaveStatusAll)
            $this->selenium->click($this->checkLeaveStatusAll);



        if ($leaveStatusRejected)
            $this->selenium->click($this->checkLeaveStatusRejected);
        if ($leaveStatusCanceled)
            $this->selenium->click($this->checkLeaveStatusCanceled);
        if ($leaveStatusPending)
            $this->selenium->click($this->checkLeaveStatusPending);
        if ($leaveStatusScheduled)
            $this->selenium->click($this->checkLeaveStatusScheduled);
        if ($leaveStatusTaken)
            $this->selenium->click($this->checkLeaveStatusTaken);
        if ($employeeName)
            $this->selenium->type($this->txtEmployeeName, $employeeName);
        if ($subUnit)
            $this->selenium->select($this->cmbSubUnit, $subUnit);
        if ($terminated)
            $this->selenium->click($this->checkTerminated);
        
        $this->selenium->click($this->btnSearch);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function getSuccessfullyFoundMessage() {
        return $this->selenium->getText("");
    }

}