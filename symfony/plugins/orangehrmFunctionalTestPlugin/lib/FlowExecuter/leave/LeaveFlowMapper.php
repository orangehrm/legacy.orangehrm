<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeaveFlowMapper
 *
 * @author madusani
 */
class LeaveFlowMapper {

    private $selenium;

    public function __construct($selenuim) {
        $this->selenium = $selenuim;
    }

    public function getFlowObject($string) {

        switch ($string) {

            case 'LogIn':
                $LogIn = new LogInFlow($this->selenium);
                return $LogIn;
            case 'LogOut':
                $LogOut = new LogOutFlow($this->selenium);
                return $LogOut;
            case 'Apply':
                $apply = new ApplyLeaveFlow($this->selenium);
                return $apply;
            case 'Assign':
                $assign = new AssignLeaveFlow($this->selenium);
                return $assign;
            case 'VerifyLeaveList':
                $leaveList = new ViewLeaveListFlow($this->selenium);
                return $leaveList;
            case 'VerifyLeaveSummary':
                $leaveSummary = new ViewLeaveSummaryFlow($this->selenium);
                return $leaveSummary;
            case 'Approve':
                $approveLeaverequest = new LeaveRequestAction($this->selenium);
                return $approveLeaverequest;
            case 'Reject':
                $rejectLeaverequest = new LeaveRequestAction($this->selenium);
                return $rejectLeaverequest;
            case 'Cancel':
                $cancelLeaverequest = new LeaveRequestAction($this->selenium);
                return $cancelLeaverequest;
            case 'VerifyMyLeaveList':
                $myLeaveList = new ViewMyLeaveListFlow($this->selenium);
                return $myLeaveList;
            case 'CancelMyLeaverequest':
                $cancelMyLeaverequest = new CancelMyLeaveRequest($this->selenium);
                return $cancelMyLeaverequest;
            case 'VerifyDetailedList':
                $detailedLeaveList = new ViewDetailedLeaveListFlow($this->selenium);
                return $detailedLeaveList;
            case 'VerifyMyDetailedList':
                $myDetailedLeaveList = new ViewMyDetailedLeaveListFlow($this->selenium);
                return $myDetailedLeaveList;
        }
    }

}