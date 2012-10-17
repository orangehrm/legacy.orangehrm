<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewAttendanceRecord
 *
 * @author Faris
 */
class ViewAttendanceRecord extends Page {

    public $date;
    public $btnEdit;
    public $btnDelet;
    public $body;
    public $config;
    public $employee;
    public $btnAddAttendanceRecord;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->calender = "//form[@id='reportForm']/table/tbody/tr/td/input[@id='DateBtn']";
        $this->employee = "employee";
        $this->date = "attendance_date";
        $this->btnEdit = "btnEdit";
        $this->btnDelet = "btnDelete";
        $this->btnAddAttendanceRecordPunchIn = "btnPunchIn";
        $this->btnAddAttendanceRecordPunchOut = "btnPunchOut";
    }

    public function viewAttendanceRecords($date, $employee=null) {
        $this->selenium->selectFrame("relative=top");
        if ($employee) {
            $this->selenium->type($this->employee, $employee);
        }

        //$this->selenium->type($this->employee, $emp);
//        $this->selenium->type($this->date, $date);
//        $this->selenium->click($this->date);
        Calender::selectDateUsingCalendar($this->selenium, $this->date, $date);
        
        sleep(3);
        //$this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
    }

    public function clickEditAttendanceRecord() {

        $this->selenium->selectFrame("relative=top");
        
        $this->selenium->click($this->btnEdit);

        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        return new AttendanceRecordEditView($this->selenium);
    }

    public function clickAddAttendanceRecord() {
        $this->selenium->selectFrame("relative=top");
        if ($this->selenium->isElementPresent($this->btnAddAttendanceRecordPunchIn)) {
            $this->selenium->click($this->btnAddAttendanceRecordPunchIn);
        } else {
            $this->selenium->click($this->btnAddAttendanceRecordPunchOut);
        }

        
        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
        return new AddAttendanceRecord($this->selenium);
    }

}