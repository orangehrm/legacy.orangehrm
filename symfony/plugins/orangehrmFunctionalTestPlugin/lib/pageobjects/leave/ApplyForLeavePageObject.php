<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApplyForLeavePageObject
 *
 * @author madusani
 */
class ApplyForLeavePageObject extends Component {

    public $cmbLeaveType = "//select[@id='applyleave_txtLeaveType']";
    public $txtFromDate = "//input[@id='applyleave_txtFromDate']";
    public $txtToDate = "//input[@id='applyleave_txtToDate']";
    public $txtComment = "//textarea[@id='applyleave_txtComment']";
    public $btnApply = "//input[@id='saveBtn']";
    public $cmbFromTime = "//select[@id='applyleave_txtFromTime']";
    public $cmbToTime = "//select[@id='applyleave_txtToTime']";
    public $txtTotalHours = "//input[@id='applyleave_txtLeaveTotalTime']";
    public $btnCalFromDate = "//input[@id='applyleave_txtFromDate_Button']";
    public $btnCalToDate = "//input[@id='applyleave_txtToDate_Button']";

    public function __construct($selenium) {
        parent::__construct($selenium, "Apply For Leave");
    }

    public function applyForLeave($leaveType, $fromDate, $toDate, $fromTime=null, $toTime=null, $comment=null) {
        $this->selenium->selectFrame("relative=top");

        $this->selenium->select($this->cmbLeaveType, $leaveType);
        Calender::selectDateUsingCalendar($this->selenium, $this->btnCalFromDate, $fromDate);
        Calender::selectDateUsingCalendar($this->selenium, $this->btnCalToDate, $toDate);
        if ($this->selenium->isElementPresent($this->cmbFromTime) && $fromTime)
            $this->selenium->select($this->cmbFromTime, $fromTime);
        if ($this->selenium->isElementPresent($this->cmbToTime) && $toTime)
            $this->selenium->select($this->cmbToTime, $toTime);

        if ($comment)
            $this->selenium->type($this->txtComment, $comment);
        $this->selenium->clickAndWait($this->btnApply);
    }

    public function getSuccessfullyAssignedMessage() {
        return $this->selenium->getText("//div[@id='messageBalloon_success']");
    }

    public function getWarningMessage() {
        return $this->selenium->getText("//div[@id='messageBalloon_warning']");
    }

}