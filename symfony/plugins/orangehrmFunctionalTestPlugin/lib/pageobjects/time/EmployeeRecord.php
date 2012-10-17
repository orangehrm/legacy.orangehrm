<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeRecord
 *
 * @author Faris
 */
class EmployeeRecord extends Page {

    public $txtEmployeeName;
    public $txtAttendanceDate;
    public $btnEdit;
    public $btnDelete;
    public $btnAddAttendanceRecord;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->txtEmployeeName = "employee";
        $this->txtAttendanceDate = "attendance_date";
        $this->btnEdit = "btnEdit";
        $this->btnDelete = "btnDelete";
        $this->btnAddAttendanceRecord = "btnPunchIn";
    }

    public function viewEmployeeAttendance($employee, $date) {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->type($this->txtComments, $employee);
        $this->selenium->type($this->txtComments, $date);
    }

    public function addAttendanceRecord() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnAddAttendanceRecord);
        //we should be creating another page object for Add Attendance Record.
        return AddAttendanceRecord();
    }

    public function editAttendanceRecord() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnEdit);

        return AttendanceRecordEditView();
    }

    public function deleteAttendancerecord() {
        
    }

}