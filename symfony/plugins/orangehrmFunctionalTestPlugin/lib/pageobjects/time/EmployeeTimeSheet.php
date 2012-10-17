<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeTimeSheet
 *
 * @author Faris
 */
class EmployeeTimeSheet extends Page {

    public $btnAddTimeSheet;
    public $startDatesDropDown;
    public $btnEdit;
    public $btnApprove;
    public $btnReject;
    public $txtComments;
    public $timedate;
    public $btnSubmit;
    public $selenium;
    public $btnCalender;
    public $config;
    public $statusVerify;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->selenium = $selenium;
        $this->btnAddTimeSheet = "btnAddTimesheet";
        $this->startDatesDropDown = "startDates";
        $this->btnEdit = "btnEdit";
        $this->btnApprove = "btnApprove";
        $this->btnReject = "btnReject";

        $this->txtComments = "txtComment";
        $this->timedate = "time_date";
        $this->btnSubmit = "btnSubmit";
        $this->btnCalender = "//form[@id='createTimesheetForm']/input[@id='DateBtn']";
    }

    public function approveTimeSheet($comment=null) {

        $this->selenium->selectFrame("relative=top");
        if ($comment != null) {
            $this->selenium->type($this->txtComments, $comment);
        }
        $this->selenium->clickAndWait($this->btnApprove);
        
    }

    public function rejectTimeSheet($comment=null) {
        $this->selenium->selectFrame("relative=top");
        if ($comment != null) {
            $this->selenium->type($this->txtComments, $comment);
        }
        $this->selenium->clickAndWait($this->btnReject);
    }

    public function selectWeekForTimeSheet($dateRange) {
        $this->selenium->selectFrame("relative=top");
        if(!$this->selenium->isSomethingSelected($this->startDatesDropDown,$dateRange)){
        $this->selenium->select($this->startDatesDropDown, $dateRange);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        }
    }

    public function addTimeSheet($dateForTimeSheet) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAddTimeSheet);
        $this->selenium->type($this->timedate, $dateForTimeSheet);
        $this->selenium->click($this->btnCalender);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        
    }

    public function clickEditTimeSheet() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnEdit);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        return new TimeSheetEditView($this->selenium);
    }

    public function clickSubmitTimeSheet() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnSubmit);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function verifyStatus($status) {
        if ($this->selenium->isElementPresent("//form[@id='timesheetFrm']/div/div[1]/h4//.[text()=contains(.,'" . $status . "')]")) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}