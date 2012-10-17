<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeaveRequestConfirmationFlow
 *
 * @author madusani
 */
class LeaveRequestAction extends Flow {

    public $dataArray;
    public $selenium;
    public $menu;
    public $leaveList;

    public function __construct($selenium) {
        $this->selenium = $selenium;
        $this->menu = new Menu();
        $this->leaveList = new ViewLeaveListPageObject($selenium);
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {
        $this->viewLeaveList = Menu::goToLeaveList($this->selenium);
        $empFullName = Helper::getFullName($this->dataArray["firstName"], $this->dataArray["lastName"], $this->dataArray["middleName"]);
        $this->viewLeaveList->searchLeaveRecords($this->dataArray["fromDate"], $this->dataArray["toDate"], true, $leaveStatusRejected = false, $leaveStatusCanceled = false, $leaveStatusPending = false, $leaveStatusScheduled = false, $leaveStatusTaken = false, $empFullName, $subUnit = null, $terminated = false);
        $leaveListDisplayDate = $this->dataArray["fromDate"] . " to " . $this->dataArray["toDate"];
        $this->viewLeaveList->list->performActionOnLeaveRequest($empFullName, $leaveListDisplayDate, $this->dataArray["Action"]);

        if ($verify) {
            return $this->verify();
        }
    }

    public function verify() {
        return true;
    }

}