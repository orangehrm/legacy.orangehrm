<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LeavePrerequisiteHandler
 *
 * @author madusani
 */
class LeavePrerequisiteHandler {

    public $fixture;
    public $fixturePath;

    public function __construct($prerequisiteFixturePath) {

        $this->fixturePath = sfConfig::get('sf_plugins_dir') . "/orangehrmFunctionalTestPlugin/test/leave/testdata/" . $prerequisiteFixturePath;
        $this->fixture = sfYaml::load($this->fixturePath);
        $this->ensurePrerequisites();
    }

    public function getPrerequisiteDetailsIntoAMergedArray($leaveRequestID) {
        $leaveRequestDetails = $this->getLeaveRequestRecordDetails($leaveRequestID);
        $leaveSummaryDetails = $this->getLeaveSummaryRecordDetails($leaveRequestDetails["empNumber"], $leaveRequestDetails["leave_type_id"], $leaveRequestDetails["leave_period_id"]);
        $employeeDetails = $this->getEmployeeDetails($leaveRequestDetails["empNumber"]);
        $mergedDetails = array_merge($leaveRequestDetails, $leaveSummaryDetails, $employeeDetails);
        return $mergedDetails;
    }

    public function ensurePrerequisites() {

        $externalPrerequisites = new ExternalDependencyHandler();

        $externalPrerequisites->ensureDependencies("leave");


        TestDataService::populate($this->fixturePath);
    }

    public function getLeaveRequestRecordDetails($leaveRequestID) {

        foreach ($this->fixture["LeaveRequest"] as $leaveRequestRecord) {
            if ($leaveRequestRecord["leave_request_id"] == $leaveRequestID) {
                return $leaveRequestRecord;
            }
        }
    }

    public function getLeaveSummaryRecordDetails($empNo, $leaveTypeID, $leavePeriodID) {
        foreach ($this->fixture["EmployeeLeaveEntitlement"] as $leaveSummaryRecord) {
            if ($leaveSummaryRecord["employee_id"] == $empNo
                    && $leaveSummaryRecord["leave_type_id"] == $leaveTypeID
                    && $leaveSummaryRecord["leave_period_id"] == $leavePeriodID) {
                return $leaveSummaryRecord;
            }
        }
        return null;
    }

    public function getEmployeeDetails($empID) {

        $depHandler = new ExternalDependencyHandler();
        $leaveExternalPrerequisites = sfYaml::load($depHandler->leaveFixture);
        foreach ($leaveExternalPrerequisites["Employee"] as $empRecord) {
            if ($empRecord["emp_number"] == $empID) {
                return $empRecord;
            }
        }
        return null;
    }

}