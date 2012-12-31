<?php

class LeaveAssignmentService extends AbstractLeaveAllocationService {

    protected $leaveEntitlementService;
    protected $dispatcher;

    /**
     * Get LeaveEntitlementService
     * @return LeaveEntitlementService
     * 
     */
    public function getLeaveEntitlementService() {
        if (!($this->leaveEntitlementService instanceof LeaveEntitlementService)) {
            $this->leaveEntitlementService = new LeaveEntitlementService();
        }
        return $this->leaveEntitlementService;
    }
    
    /**
     * Set LeaveEntitlementService
     * @param LeaveEntitlementService $service 
     */
    public function setLeaveEntitlementService(LeaveEntitlementService $service) {
        $this->leaveEntitlementService = $service;
    }
    
    /**
     * Set dispatcher.
     * 
     * @param $dispatcher
     */
    public function setDispatcher($dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    public function getDispatcher() {
        if(is_null($this->dispatcher)) {
            $this->dispatcher = sfContext::getInstance()->getEventDispatcher();
        }
        return $this->dispatcher;
    }  
    
    /**
     *
     * @param array $leaveAssignmentData
     * @return bool
     */
    public function assignLeave(LeaveParameterObject $leaveAssignmentData) {

        $employeeId = $leaveAssignmentData->getEmployeeNumber();

        /* Check whether employee exists */
        if (empty($employeeId)) {
            throw new LeaveAllocationServiceException('Invalid Employee');
        }

        if ($this->applyMoreThanAllowedForADay($leaveAssignmentData)) {
            throw new LeaveAllocationServiceException('Failed to Assign: Work Shift Length Exceeded');
        } else {
            if (!$this->hasOverlapLeave($leaveAssignmentData)) {
                return $this->saveLeaveRequest($leaveAssignmentData);
//                return true;
            }
        }
    }

    /**
     * Saves Leave Request and Sends Notification
     * 
     * @param LeaveParameterObject $leaveAssignmentData 
     * 
     */
    protected function saveLeaveRequest(LeaveParameterObject $leaveAssignmentData) {

        $leaveRequest = $this->generateLeaveRequest($leaveAssignmentData);

          $leaveType = $this->getLeaveTypeService()->readLeaveType($leaveAssignmentData->getLeaveType());
//        $leaveRequest->setLeaveTypeName($leaveType->getLeaveTypeName());

//        if (is_null($leaveRequest->getLeavePeriodId())) {
//            if ($this->getLeavePeriodService()->isWithinNextLeavePeriod(strtotime($leaveRequest->getDateApplied()))) {
//                $nextLeavePeriod = $this->getLeavePeriodService()->createNextLeavePeriod($leaveRequest->getDateApplied());
//                $leaveRequest->setLeavePeriodId($nextLeavePeriod->getLeavePeriodId());
//            }
//        }

        $leaveDays = $this->createLeaveObjectListForAppliedRange($leaveAssignmentData);        
        
        $empNumber = $leaveAssignmentData->getEmployeeNumber();
        
        $nonHolidayLeaveDays = array();
        
        $holidayCount = 0;
        $holidays = array(Leave::LEAVE_STATUS_LEAVE_WEEKEND, Leave::LEAVE_STATUS_LEAVE_HOLIDAY);
        foreach ($leaveDays as $k => $leave) {
            if (in_array($leave->getStatus(), $holidays)) {
                $holidayCount++;
            } else {
                $nonHolidayLeaveDays[] = $leave;
            }
        }        
                
        if (count($nonHolidayLeaveDays) > 0) {
            $strategy = $this->getLeaveEntitlementService()->getLeaveEntitlementStrategy();            
            $entitlements = $strategy->handleLeaveCreate($empNumber, $leaveType->getId(), $nonHolidayLeaveDays, true);

            if ($entitlements == false) {
                throw new LeaveAllocationServiceException('Leave Balance Exceeded');
            }
        }

        /* This is to see whether employee applies leave only during weekends or standard holidays */
        if ($holidayCount != count($leaveDays)) {
            if ($this->isEmployeeAllowedToApply($leaveType)) { // TODO: Should this be checked on Assign??
                try {
                    
                    $user = sfContext::getInstance()->getUser();
                    $loggedInUserId = $user->getAttribute('auth.userId');
                    $loggedInEmpNumber = $user->getAttribute('auth.empNumber');
        
                    $leaveRequest = $this->getLeaveRequestService()->saveLeaveRequest($leaveRequest, $leaveDays, $entitlements);
                    $leaveComment = $leaveRequest->getComments();
                                   
                    if (!empty($loggedInEmpNumber)) {
                        $employee = $this->getEmployeeService()->getEmployee($loggedInEmpNumber);
                        $createdBy = $employee->getFullName();
                    } else {
                        $createdBy = $user->getAttribute('auth.firstName');
                    }
                    $this->getLeaveRequestService()->saveLeaveRequestComment($leaveRequest->getId(), 
                            $leaveComment, $createdBy, $loggedInUserId, $loggedInEmpNumber);

//                    if ($this->isOverlapLeaveRequest($leaveAssignmentData)) {
//                        $this->getLeaveRequestService()->modifyOverlapLeaveRequest($leaveRequest, $leaveDays);
//                    }

                    /* Send notification to the when leave is assigned; TODO: Move to action? */
                    $eventData = array('request' => $leaveRequest, 'days' => $leaveDays, 'empNumber' => $_SESSION['empNumber']);
                    $this->getDispatcher()->notify(new sfEvent($this, LeaveEvents::LEAVE_ASSIGN, $eventData));

//                    return true;
                    return $leaveRequest;
                } catch (Exception $e) {
                    throw new LeaveAllocationServiceException('Error saving leave request');
                }
            }
        } else {
            throw new LeaveAllocationServiceException('Failed to Submit: No Working Days Selected');
        }
    }

    /**
     *
     * @param type $isWeekend
     * @param type $isHoliday
     * @param type $leaveDate
     * @return type 
     */
    public function getLeaveRequestStatus($isWeekend, $isHoliday, $leaveDate) {
        $status = null;

        if ($isWeekend) {
            return Leave::LEAVE_STATUS_LEAVE_WEEKEND;
        }

        if ($isHoliday) {
            return Leave::LEAVE_STATUS_LEAVE_HOLIDAY;
        }

        if (strtotime($leaveDate) < strtotime(date('Y-m-d'))) {
            $status = Leave::LEAVE_STATUS_LEAVE_TAKEN;
        } else {
            $status = Leave::LEAVE_STATUS_LEAVE_APPROVED;
        }

        return $status;
    }

}