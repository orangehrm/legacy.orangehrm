<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewLeaveListFlow
 *
 * @author madusani
 */
class ViewLeaveListFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $menu;
    public $viewLeaveList;

    public function __construct($selenium) {
        $this->selenium = $selenium;
        $this->menu = new Menu();
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {
        $this->viewLeaveList = Menu::goToLeaveList($this->selenium);

        $this->viewLeaveList->searchLeaveRecords($this->dataArray["fromDate"], $this->dataArray["toDate"], $this->dataArray["leaveStatus"], $this->dataArray["isRejected"], $this->dataArray["isCanceled"], $this->dataArray["isPending"], $this->dataArray["isScheduled"], $this->dataArray["isTaken"], $this->dataArray["empName"]);


        if ($verify) {
            return $this->verify();
        }
    }

    public function verify() {


        $leaveListDisplayDate = $this->dataArray["fromDate"] . " to " . $this->dataArray["toDate"];
            
        if ($leaveListDisplayDate) {

            if (!$this->viewLeaveList->list->isLeaveItemPresentInColumn("Date", $leaveListDisplayDate)) {
            
                return false;
            }
        }

        if ($this->dataArray["NumberOfDays"]) {


            if ($this->viewLeaveList->list->getItemOfFirstRecord("NumberOfDays") != $this->dataArray["NumberOfDays"]) {
            
                return false;
            }
        }

        if ($this->dataArray["Status"]) {
            if ($this->viewLeaveList->list->getItemOfFirstRecord("Status") != $this->dataArray["Status"]) {
                
                return false;
            }
        }
//        if ($this->dataArray["Action"]) {
//            if ($this->viewLeaveList->list->getItemOfFirstRecord("Action") != $this->dataArray["Action"]) {
//
//
//                return false;
//            }
//        }

        return true;
    }

}