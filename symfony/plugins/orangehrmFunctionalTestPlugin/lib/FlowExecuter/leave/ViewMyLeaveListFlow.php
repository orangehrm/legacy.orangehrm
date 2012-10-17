<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewMyLeaveListFlow
 *
 * @author madusani
 */
class ViewMyLeaveListFlow extends Flow {

    public $dataArray;
    public $selenium;
    public $menu;
    public $viewMyLeaveList;

    public function __construct($selenium) {
        $this->selenium = $selenium;
        $this->menu = new Menu();
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {
        $this->viewMyLeaveList = Menu::goToMyLeaveList($this->selenium);
        $this->viewMyLeaveList->searchLeaveRecords($this->dataArray["fromDate"], $this->dataArray["toDate"], $this->dataArray["leaveStatus"], $this->dataArray["isRejected"], $this->dataArray["isCanceled"], $this->dataArray["isPending"], $this->dataArray["isScheduled"], $this->dataArray["isTaken"]);

        if ($verify) {
            return $this->verify();
        }
    }

    public function verify() {


        $leaveListDisplayDate = $this->dataArray["fromDate"] . " to " . $this->dataArray["toDate"];
        if ($this->dataArray[$leaveListDisplayDate]) {
            if (!$this->viewMyLeaveList->list->isLeaveItemPresentInColumn("Date", $leaveListDisplayDate)) {
                
                return false;
            }
        }

        if ($this->dataArray["NumberOfDays"]) {


            if ($this->viewMyLeaveList->list->getItemOfFirstRecord("NumberOfDays") != $this->dataArray["NumberOfDays"]) {
                
                return false;
            }
        }

        if ($this->dataArray["Status"]) {
            if ($this->viewMyLeaveList->list->getItemOfFirstRecord("Status") != $this->dataArray["Status"]) {
                echo "returning false3";
                return false;
            }
        }
//        if ($this->dataArray["Action"]) {
//
//            if ($this->viewMyLeaveList->list->getItemOfFirstRecord("Action") != $this->dataArray["Action"]) {
//
//                return false;
//            }
//        }

        return true;
    }

}