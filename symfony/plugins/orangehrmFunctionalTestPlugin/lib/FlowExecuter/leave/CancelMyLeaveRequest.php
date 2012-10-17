<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CancelMyLeaveRequest
 *
 * @author madusani
 */
class CancelMyLeaveRequest extends Flow {

    public $dataArray;
    public $selenium;
    public $menu;
    public $leaveList;

    public function __construct($selenium) {
        $this->selenium = $selenium;
        $this->menu = new Menu();
        $this->leaveList = new ViewMyLeaveListPageObject($selenium);
    }

    public function init($dataArray) {
        $this->dataArray = $dataArray;
    }

    public function execute($verify=true) {
        $this->viewMyLeaveList = Menu::goToMyLeaveList($this->selenium);
        $empFullName = Helper::getFullName($this->dataArray["firstName"], $this->dataArray["lastName"], $this->dataArray["middleName"]);
        $this->viewMyLeaveList->searchLeaveRecords($this->dataArray["fromDate"], $this->dataArray["toDate"], true, false, false, false, false, false);
        $leaveListDisplayDate = $this->dataArray["fromDate"] . " to " . $this->dataArray["toDate"];
        $this->viewMyLeaveList->list->performActionOnLeaveRequest($empFullName, $leaveListDisplayDate, "Cancel");

        if ($verify) {
            return $this->verify();
        }
    }

    public function verify() {
        return true;
    }

}