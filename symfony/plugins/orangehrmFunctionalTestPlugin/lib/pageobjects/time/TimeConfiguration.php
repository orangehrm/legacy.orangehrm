<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TimeConfiguration
 *
 * @author Faris
 */
class TimeConfiguration extends Page {

    public $chkEmpChangePunch;
    public $chkEmpEditDeleteOwnRecord;
    public $supervisorAddEditDeleteRecord;
    public $btnSave;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->chkEmpChangePunch = "attendance_configuration1";
        $this->chkEmpEditDeleteOwnRecord = "attendance_configuration2";
        $this->supervisorAddEditDeleteRecord = "attendance_configuration3";
        $this->btnSave = "btnSave";
    }

    public function enableEmployeeToChangeCurrentTime() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->chkEmpChangePunch);
        $this->selenium->click($this->btnSave);
    }

    public function enableEmployeeToEditDeleteOnRecord() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->chkEmpEditDeleteOwnRecord);
        $this->selenium->click($this->btnSave);
    }

    public function enableSupervisorToModifySubordinatesRecord() {
        $this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->supervisorAddEditDeleteRecord);
        $this->selenium->click($this->btnSave);
    }

}