<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewDetailedLeaveListFlow
 *
 * @author intel
 */
class ViewDetailedLeaveListFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $detailedLeaveList;
    public $viewLeaveList;

    public function __construct($selenium) {
        $menu = new Menu();
        $this->selenium = $selenium;
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {
        $this->viewLeaveList = Menu::goToLeaveList($this->selenium);
        $this->viewLeaveList->searchLeaveRecords($this->dataArray["fromDate"], $this->dataArray["toDate"], $this->dataArray["leaveStatus"], false, false, false, false, false, $this->dataArray["empName"]);

        if ($this->dataArray["fromDate"] == $this->dataArray["toDate"]) {
            $leaveListDisplayDate = $this->dataArray["fromDate"];
        } else {
            $leaveListDisplayDate = $this->dataArray["fromDate"] . " to " . $this->dataArray["toDate"];
        }

        $this->detailedLeaveList = $this->viewLeaveList->list->clickOnTheItem("Date", $leaveListDisplayDate);

        
        if ($this->dataArray["ActionToBePerformed"]) {
            $this->detailedLeaveList->performActionOnLeaveRequest($this->dataArray["dateToBeChecked"], $this->dataArray["ActionToBePerformed"]);
            
        }
        if ($verify) {
            return $this->verify();
        }
    }

    public function verify() {
        if ($this->dataArray["Duration"]) {
            if ($this->detailedLeaveList->getItemOfSpecifiedRecord("Duration", $this->dataArray["dateToBeChecked"]) != $this->dataArray["Duration"]) {
//                echo "\nitem ". $this->detailedLeaveList->getItemOfSpecifiedRecord("Duration", $this->dataArray["dateToBeChecked"]);
//                echo "\nexpected value " . $this->dataArray["Duration"];
//                echo "\nreturning false1";
                return false;
            }
        }

        if ($this->dataArray["Status"]) {
            if ($this->detailedLeaveList->getItemOfSpecifiedRecord("Status", $this->dataArray["dateToBeChecked"]) != $this->dataArray["Status"]) {
//                
//                echo "\nitem ". $this->detailedLeaveList->getItemOfSpecifiedRecord("Status", $this->dataArray["dateToBeChecked"]);
//                echo "\nexpected value " . $this->dataArray["Status"];
//                echo "\nreturning false2";
                return false;
            }
        }
//        if($this->dataArray["Action"]){
//           
//           if($this->detailedLeaveList->getItemOfSpecifiedRecord("Action",  $this->dataArray["dateToBeChecked"]) != $this->dataArray["Action"]){
//               
//                return false;
//            }
//        }

        return true;
    }

}