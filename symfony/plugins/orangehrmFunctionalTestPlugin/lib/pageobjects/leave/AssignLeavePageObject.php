<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AssignLeavePageObject
 *
 * @author madusani
 */
class AssignLeavePageObject extends Component {

    public $txtEmployeeName = "//input[@id='assignleave_txtEmployee_empName']";
    public $cmbLeaveType = "//select[@id='assignleave_txtLeaveType']";
    public $txtFromDate = "//input[@id='assignleave_txtFromDate']";
    public $txtToDate = "//input[@id='assignleave_txtToDate']";
    public $txtComment = "//textarea[@id='assignleave_txtComment']";
    public $btnAssign = "//input[@id='saveBtn']";
    public $cmbFromTime = "//select[@id='assignleave_txtFromTime']";
    public $cmbToTime = "//select[@id='assignleave_txtToTime']";
    public $txtTotalHours = "//input[@id='assignleave_txtLeaveTotalTime']";
    public $btnCalFromDate = "//input[@id='assignleave_txtFromDate_Button']";
    public $btnCalToDate = "//input[@id='assignleave_txtToDate_Button']";

    public function __construct($selenium) {
        parent::__construct($selenium, "Assign Leave");
    }

    public function assignLeaveForEmployee($employeeName, $leaveType, $fromDate, $toDate, $fromTime=null, $toTime=null, $comment=null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtEmployeeName, $employeeName);
//        $this->selenium->click($this->txtEmployeeName);
//        $this->selenium->click("//div[@class='ac_results']");
        $this->selenium->select($this->cmbLeaveType, $leaveType);
        Calender::selectDateUsingCalendar($this->selenium, $this->btnCalFromDate, $fromDate);
        Calender::selectDateUsingCalendar($this->selenium, $this->btnCalToDate, $toDate);
        if ($this->selenium->isElementPresent($this->cmbFromTime) && $fromTime) {

            $this->selenium->select($this->cmbFromTime, $fromTime);
        }
        if ($this->selenium->isElementPresent($this->cmbToTime) && $toTime) {

            $this->selenium->select($this->cmbToTime, $toTime);
        }
        if ($comment) {

            $this->selenium->type($this->txtComment, $comment);
        }
        $this->selenium->clickAndWait($this->btnAssign);
        
    }

    public function getSuccessfullyAssignedMessage() {
        return $this->selenium->getText("//div[@id='messageBalloon_success']");
    }

}