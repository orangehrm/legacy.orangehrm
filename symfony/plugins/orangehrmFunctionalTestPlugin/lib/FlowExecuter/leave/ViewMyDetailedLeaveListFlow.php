<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewMyDetailedLeaveListFlow
 *
 * @author intel
 */
class ViewMyDetailedLeaveListFlow {

    public $dataArray;
    public $selenium;
    public $myDetailedLeaveList;
    public $viewMyLeaveList;

    public function __construct($selenium) {
        $menu = new Menu();
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {

        $this->viewMyLeaveList = Menu::goToMyLeaveList($this->selenium);
        $this->viewMyLeaveList->searchLeaveRecords($this->dataArray["fromDate"], $this->dataArray["toDate"], $this->dataArray["leaveStatus"]);

        if ($this->dataArray["fromDate"] == $this->dataArray["toDate"]) {
            $leaveListDisplayDate = $this->dataArray["fromDate"];
        } else {
            $leaveListDisplayDate = $this->dataArray["fromDate"] . " to " . $this->dataArray["toDate"];
        }

        $this->myDetailedLeaveList = $this->viewMyLeaveList->list->clickOnTheItem("Date", $leaveListDisplayDate);
        
        if ($this->dataArray["ActionToBePerformed"]) {
            $this->myDetailedLeaveList->performActionOnLeaveRequest($this->dataArray["dateToBeChecked"], $this->dataArray["ActionToBePerformed"]);
            
        }
        if ($verify) {
            return $this->verify();
        }
    }

    public function verify() {
        if ($this->dataArray["Duration"]) {
            if ($this->myDetailedLeaveList->getItemOfSpecifiedRecord("Duration", $this->dataArray["dateToBeChecked"]) != $this->dataArray["Duration"]) {
                
                return false;
            }
        }

        if ($this->dataArray["Status"]) {
            if ($this->myDetailedLeaveList->getItemOfSpecifiedRecord("Status", $this->dataArray["dateToBeChecked"]) != $this->dataArray["Status"]) {
                
                return false;
            }
        }
//        if($this->dataArray["Action"]){
//            if(!$this->myDetailedLeaveList->getItemOfSpecifiedRecord("Action",  $this->dataArray["dateToBeChecked"]) == $this->dataArray["Action"]){
//                
//                return false;
//            }
//        }

        return true;
    }

}