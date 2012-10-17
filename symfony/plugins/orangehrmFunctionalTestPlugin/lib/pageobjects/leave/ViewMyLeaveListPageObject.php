<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewMyLeaveListPageObject
 *
 * @author madusani
 */
class ViewMyLeaveListPageObject extends ViewLeaveListPageObject {

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->list = new MyLeaveList($selenium, "//form[@id='frmList_ohrmListComponent']", true);
    }

//
//    public function searchLeaveRecords($leaveFromDate, $leaveToDate, $leaveStatusAll=false, $leaveStatusRejected=false, $leaveStatusCanceled=false, $leaveStatusPending=false, $leaveStatusScheduled=false, $leaveStatusTaken=false) {
//        $this->selenium->selectFrame("relative=top");
//        $this->selenium->type($this->txtFromDate, $leaveFromDate);
//        $this->selenium->type($this->txtToDate, $leaveToDate);
//        if ($leaveStatusAll)
//            $this->selenium->click($this->checkLeaveStatusAll);
//        if ($leaveStatusRejected)
//            $this->selenium->click($this->checkLeaveStatusRejected);
//        if ($leaveStatusCanceled)
//            $this->selenium->click($this->checkLeaveStatusCanceled);
//        if ($leaveStatusPending)
//            $this->selenium->click($this->checkLeaveStatusPending);
//        if ($leaveStatusScheduled)
//            $this->selenium->click($this->checkLeaveStatusScheduled);
//        if ($leaveStatusTaken)
//            $this->selenium->click($this->checkLeaveStatusTaken);
//        $this->selenium->click($this->btnSearch);
//        $this->selenium->waitForPageToLoad($this->config->getTimeoutValue());
//    }
}