<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeavePeriod
 *
 * @author madusani
 */
class LeavePeriodPageObject extends Component {

    public $cmbStartMonth = "leaveperiod_cmbStartMonth";
    public $cmbStartDate = "leaveperiod_cmbStartDate";
    public $btnSave = "btnEdit";
    public $btnReset = "btnReset";
    public $config;
    public $cmbStartMonthForNonLeapYear = "leaveperiod_cmbStartMonthForNonLeapYears";
    public $cmbStartDateForNonLeapYear = "leaveperiod_cmbStartDateForNonLeapYears";

    public function __construct($selenium) {
        parent::__construct($selenium, "Leave Period");
        $this->config = new TestConfig();
    }

    public function defineLeavePeriod($startingMonth, $startingDate, $startingMonthForNonLeapYear = null, $startingDateForNonLeapYear =null) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->select($this->cmbStartMonth, $startingMonth);
        sleep(10);
        $this->selenium->select($this->cmbStartDate, $startingDate);
        sleep(10);
        if ($this->selenium->isElementPresent($this->cmbStartMonthForNonLeapYear))
            $this->selenium->select($this->cmbStartMonthForNonLeapYear, $startingMonthForNonLeapYear);
        sleep(10);
        if ($this->selenium->isElementPresent($this->cmbStartDateForNonLeapYear))
            $this->selenium->select($this->cmbStartDateForNonLeapYear, $startingDateForNonLeapYear);
        sleep(10);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function editLeavePeriod($startingMonth, $startingDate, $startingMonthForNonLeapYear=null, $startingDateForNonLeapYear=null) {
        $this->selenium->click($this->btnSave);
        $this->defineLeavePeriod($startingMonth, $startingDate, $startingMonthForNonLeapYear = null, $startingDateForNonLeapYear = null);
    }

    public function getSuccessfullySavedMessage() {
        return $this->selenium->getText("//div[@id='messagebar']");
    }

    public function getCurrentLeavePeriod() {
        return $this->selenium->getText("//tr[10]/td[2]");
    }

}