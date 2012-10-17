<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewLeaveSummaryFlow
 *
 * @author madusani
 */
class ViewLeaveSummaryFlow {

    public $dataArray;
    public $selenium;
    public $menu;
    public $viewLeaveSummary;

    public function __construct($selenium) {
        $this->selenium = $selenium;
        $this->menu = new Menu();
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {
        $this->viewLeaveSummary = Menu::goToLeaveSummary($this->selenium);
        if ($this->dataArray["empName"]) {
            $this->viewLeaveSummary->viewLeaveSummaryRecords($this->dataArray["leavePeriod"], $this->dataArray["empName"], null, null, null, $this->dataArray["leaveTypeName"]);
        } else {
            $this->viewLeaveSummary->viewLeaveSummaryRecords($this->dataArray["leavePeriod"], null, null, null, null, $this->dataArray["leaveTypeName"]);
        }

        if ($verify) {
            return $this->verify();
        }
    }

    public function verify() {

        $actualValues = $this->viewLeaveSummary->list->getLeaveSummaryDetails($this->dataArray["leaveTypeName"], $this->dataArray["empName"]);
        
        if ($this->dataArray["expectedLeaveEntitled"]) {
            if ($actualValues["LeaveEntitled"] != $this->dataArray["expectedLeaveEntitled"]) {
               
                return false;
            }
        }
        if ($this->dataArray["expectedLeaveScheduled"]) {
            if ($actualValues["LeaveScheduled"] != $this->dataArray["expectedLeaveScheduled"]) {
               
                return false;
            }
        }
        if ($this->dataArray["expectedLeaveTaken"]) {
            if ($actualValues["LeaveTaken"] != $this->dataArray["expectedLeaveTaken"]) {
                
                return false;
            }
        }
        if ($this->dataArray["expectedLeaveBalance"]) {
            if ($actualValues["LeaveBalance"] != $this->dataArray["expectedLeaveBalance"]) {
                
                return false;
            }
        }
        return true;
    }

}