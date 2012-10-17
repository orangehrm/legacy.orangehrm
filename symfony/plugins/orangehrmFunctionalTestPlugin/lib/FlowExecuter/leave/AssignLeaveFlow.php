<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AssignLeaveFlow
 *
 * @author madusani
 */
class AssignLeaveFlow {

    public $dataArray;
    public $selenium;
    public $menu;

    public function __construct($selenium) {
        $this->selenium = $selenium;
        $this->menu = new Menu();
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $assignLeave = $this->menu->goToAssignLeave($this->selenium);
        $assignLeave->assignLeaveForEmployee($this->dataArray["empName"], $this->dataArray["leaveTypeName"], $this->dataArray["fromDate"], $this->dataArray["toDate"], $this->dataArray["fromTime"], $this->dataArray["toTime"], $this->dataArray["leave_comments"]);

        if ($verify) {
            return $this->verify();
        }
    }

    public function verify() {
        return true;
    }

}