<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ApplyLeaveFlow
 *
 * @author madusani
 */
class ApplyLeaveFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $menu;
    public $applyLeave;

    public function __construct($selenium) {
        $this->selenium = $selenium;
        $this->menu = new Menu();
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->applyLeave = $this->menu->goToApplyForLeave($this->selenium);
        $this->applyLeave->applyForLeave($this->dataArray["leaveTypeName"], $this->dataArray["fromDate"], $this->dataArray["toDate"], $this->dataArray["fromTime"], $this->dataArray["toTime"], $this->dataArray["leave_comments"]);
        if ($verify) {
            return $this->verify();
        }
    }

    public function verify() {
        if ($this->dataArray["WarningMessage"]) {
            if ($this->applyLeave->getWarningMessage() != $this->dataArray["WarningMessage"]) {
               
                return false;
            }
        }
        return true;
    }

}