<?php

/**
 * PluginLeaveRequest
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginLeaveRequest extends BaseLeaveRequest {

    private $leave = null;
    private $leaveCount = null;
    private $numberOfDays = null;
    private $leaveDuration = null;
    private $statusCounter = array();
    private $workShiftHoursPerDay = null;

    // const LEAVE_REQUEST_STATUS_APPROVED = 'Scheduled';
    // const LEAVE_REQUEST_STATUS_CANCELLED = 'Cancelled';
    // const LEAVE_REQUEST_STATUS_REJECTED = 'Rejected';
    const LEAVE_REQUEST_STATUS_DIFFER = -2;

    public function getNumberOfDays() {
        $this->_fetchLeave();
        return number_format($this->numberOfDays, 2);
    }

    private function getStatusCounter() {
        return $this->statusCounter;
    }

    public function getLeaveDuration() {

        if ($this->leaveCount == 1) {
            $startTime = $this->leave[0]->getStartTime();
            $endTime = $this->leave[0]->getEndTime();

            if ((!empty($startTime) && !empty($endTime)) && ("{$startTime} {$endTime}" != '00:00:00 00:00:00')) {
                return "{$startTime} to {$endTime}";
            } else {
                $totalDuration = $this->leave[0]->getLengthHours();
                if (!empty($totalDuration)) {
                    return number_format($totalDuration, 2) . ' hours';
                } else {
                    return number_format($this->_getWorkShiftHoursPerDay(), 2) . ' hours';
                }
            }
        } else {
            return number_format($this->leaveDuration, 2) . ' hours';
        }
    }

    public function getLeaveBreakdown() {
        $this->_fetchLeave();

        $statusStrings = array();

        foreach ($this->statusCounter as $status => $count) {
            if (!is_null($status)) {
                $statusStrings[] = __(ucfirst(strtolower(Leave::getTextForLeaveStatus($status)))) . "(" . $count . ")";
            }
        }

        return implode(', ', $statusStrings);
    }

    public function getLeaveBalance() {
        /*$leaveEntitlementService = new OldLeaveEntitlementService();
        $employeeId = $this->getEmpNumber();
        $leaveTypeId = $this->getLeaveTypeId();
        $leavePeriodId = $this->getLeavePeriodId();
        
        $balance = $leaveEntitlementService->getLeaveBalance($employeeId, $leaveTypeId, $leavePeriodId);
         * 
         */
        $balance = '';
        return $balance;
    }

    private function _fetchLeave() {
        if (is_null($this->leave)) {
            $leaveRequestDao = new LeaveRequestDao();
            $this->leave = $leaveRequestDao->fetchLeave($this->getId());
            $this->_parseLeave();
        }
    }

    public function getLeaveStatusId() {
        $this->_fetchLeave();
        if ($this->isStatusDiffer()) {
            return self::LEAVE_REQUEST_STATUS_DIFFER;
        } else {
            reset($this->statusCounter);
            $firstKey = key($this->statusCounter);
            return $firstKey;
        }
    }

    public function getLeaveDateRange() {

        $this->_fetchLeave();
        $leaveCount = count($this->leave);

        if ($leaveCount == 1) {
            return set_datepicker_date_format($this->leave[0]->getDate());
        } else {
            $leaveRequestStartDate = $this->leave[0]->getDate();
            $leaveRequestEndDate = $this->leave[$leaveCount - 1]->getDate();
            return sprintf('%s %s %s', set_datepicker_date_format($leaveRequestStartDate), __('to'), set_datepicker_date_format($leaveRequestEndDate));
        }
    }

    private function _parseLeave() {
        $this->numberOfDays = 0.0;
        $this->leaveDuration = 0.0;

        // Counting leave
        $this->leaveCount = $this->leave->count();

        $this->statusCounter = array();

        foreach ($this->leave as $leave) {
            // Calculating number of days and duration
            $dayLength = (float) $leave->getLengthDays();

            //this got changed to fix sf-3019087,3044234 $hourLength = $dayLength * $this->_getWorkShiftHoursPerDay();
            $hourLength = (float) $leave->getLengthHours();
            if ($dayLength >= 1) {
                $hourLength = $dayLength * (float) $leave->getLengthHours();
            }

            if ($hourLength == 0.0) {
                $hourLength = (float) $leave->getLengthHours();
            }

            $this->leaveDuration += $hourLength;

            //if($hourLength > 0) {
            $this->numberOfDays += $dayLength;
            //}
            
            if (!$leave->isNonWorkingDay()) {
                
                // Populating leave breakdown
                
                $status = $leave->getStatus();
                $statusDayLength = ($dayLength != 0) ? $dayLength : 1;
                if ($hourLength > 0) {
                    if (array_key_exists($status, $this->statusCounter)) {
                        $this->statusCounter[$status]+= $statusDayLength;
                    } else {
                        $this->statusCounter[$status] = $statusDayLength;
                    }
                }
            }
        }

        //is there any use of this block ?
        /* if ($this->numberOfDays == 1.0) {
          $this->numberOfDays = $this->leave[0]->getLengthDays();
          } */

    }

    private function _getWorkShiftHoursPerDay() {

        if (!isset($this->workShiftHoursPerDay)) {
            $employeeWorkshift = $this->getEmployee()->getEmployeeWorkShift();
            if ($employeeWorkshift->count() > 0) {
                $this->workShiftHoursPerDay = $employeeWorkshift[0]->getWorkShift()->getHoursPerDay();
            } else {
                $this->workShiftHoursPerDay = WorkShift::DEFAULT_WORK_SHIFT_LENGTH;
            }
        }

        return $this->workShiftHoursPerDay;
    }

    private function _AreAllTaken() {

        $flag = true;

        foreach ($this->leave as $leave) {
            if ($leave->getStatus() != Leave::LEAVE_STATUS_LEAVE_TAKEN && $leave->getLengthHours() != '0.00') {
                $flag = false;
                break;
            }
        }

        return $flag;
    }

    public function isStatusDiffer() {

        if (count($this->getStatusCounter()) > 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getLeaveItems() {

        $leaveRequestDao = new LeaveRequestDao();
        return $leaveRequestDao->fetchLeave($this->getId());
    }
    
    public function getLeaveTypeName() {
        return $this->getLeaveType()->getName();
    }

}
