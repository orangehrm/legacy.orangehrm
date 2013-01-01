<?php
/*
 *
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 *
*/
class LeaveRequestService extends BaseService {

    private $leaveRequestDao ;
    private $leaveTypeService;
    private $leaveEntitlementService;
    private $leavePeriodService;
    private $holidayService;

    private $leaveStateManager;
    private $userRoleManager;
    
    private $dispatcher;

    const LEAVE_CHANGE_TYPE_LEAVE = 'change_leave';
    const LEAVE_CHANGE_TYPE_LEAVE_REQUEST = 'change_leave_request';

    /**
     *
     * @return LeaveRequestDao
     */
    public function getLeaveRequestDao() {
        if (!($this->leaveRequestDao instanceof LeaveRequestDao)) {
            $this->leaveRequestDao = new LeaveRequestDao();
        }
        return $this->leaveRequestDao;
    }

    /**
     *
     * @param LeaveRequestDao $leaveRequestDao
     * @return void
     */
    public function setLeaveRequestDao(LeaveRequestDao $leaveRequestDao) {
        $this->leaveRequestDao = $leaveRequestDao;
    }

    /**
     * @return LeaveEntitlementService
     */
    public function getLeaveEntitlementService() {
        if(is_null($this->leaveEntitlementService)) {
            $this->leaveEntitlementService = new LeaveEntitlementService();
        }
        return $this->leaveEntitlementService;
    }

    /**
     * @return LeaveTypeService
     */
    public function getLeaveTypeService() {
        if(is_null($this->leaveTypeService)) {
            $this->leaveTypeService = new LeaveTypeService();
        }
        return $this->leaveTypeService;
    }

    /**
     * Sets LeaveEntitlementService
     * @param LeaveEntitlementService $leaveEntitlementService
     */
    public function setLeaveEntitlementService(LeaveEntitlementService $leaveEntitlementService) {
        $this->leaveEntitlementService = $leaveEntitlementService;
    }

    /**
     * Sets LeaveTypeService
     * @param LeaveTypeService $leaveTypeService
     */
    public function setLeaveTypeService(LeaveTypeService $leaveTypeService) {
        $this->leaveTypeService = $leaveTypeService;
    }

    /**
     * Returns LeavePeriodService
     * @return LeavePeriodService
     */
    public function getLeavePeriodService() {
        if(is_null($this->leavePeriodService)) {
            $this->leavePeriodService = new LeavePeriodService();
            $this->leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());
        }
        return $this->leavePeriodService;
    }

    /**
     * Sets LeavePeriodService
     * @param LeavePeriodService $leavePeriodService
     */
    public function setLeavePeriodService(LeavePeriodService $leavePeriodService) {
        $this->leavePeriodService = $leavePeriodService;
    }

    /**
     * Returns HolidayService
     * @return HolidayService
     */
    public function getHolidayService() {
        if(is_null($this->holidayService)) {
            $this->holidayService = new HolidayService();
        }
        return $this->holidayService;
    }

    /**
     * Sets HolidayService
     * @param HolidayService $holidayService
     */
    public function setHolidayService(HolidayService $holidayService) {
        $this->holidayService = $holidayService;
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
     * Get User role manager instance
     * @return AbstractUserRoleManager
     */
    public function getUserRoleManager() {
        if (!($this->userRoleManager instanceof AbstractUserRoleManager)) {
            $this->userRoleManager = UserRoleManagerFactory::getUserRoleManager();
        }
        return $this->userRoleManager;
    }

    /**
     * Set user role manager instance
     * @param AbstractUserRoleManager $userRoleManager
     */
    public function setUserRoleManager(AbstractUserRoleManager $userRoleManager) {
        $this->userRoleManager = $userRoleManager;
    }
    
    /**
     *
     * @param LeaveRequest $leaveRequest
     * @param Leave $leave
     * @return boolean
     */
    public function saveLeaveRequest( LeaveRequest $leaveRequest , $leaveList, $entitlements) {
        return $this->getLeaveRequestDao()->saveLeaveRequest($leaveRequest, $leaveList, $entitlements);
    }
    
    public function saveLeaveRequestComment($leaveRequestId, $comment, $createdBy, $loggedInUserId, $loggedInEmpNumber) {
        return $this->getLeaveRequestDao()->saveLeaveRequestComment($leaveRequestId, $comment, $createdBy, $loggedInUserId, $loggedInEmpNumber);
    }

    public function saveLeaveComment($leaveId, $comment, $createdBy, $loggedInUserId, $loggedInEmpNumber) {
        return $this->getLeaveRequestDao()->saveLeaveComment($leaveId, $comment, $createdBy, $loggedInUserId, $loggedInEmpNumber);
    }
    
    public function getLeaveRequestComments($leaveRequestId) {
        return $this->getLeaveRequestDao()->getLeaveRequestComments($leaveRequestId);
    }

    public function getLeaveComments($leaveId) {
        return $this->getLeaveRequestDao()->getLeaveComments($leaveId);
    }    
    
    /**
     *
     * @param Employee $employee
     * @return LeaveType Collection
     */
    public function getEmployeeAllowedToApplyLeaveTypes(Employee $employee) {

        try {
            $leaveEntitlementService    = $this->getLeaveEntitlementService();                $strategy = $this->getLeaveEntitlementService()->getLeaveEntitlementStrategy();     

            $leaveTypeService           = $this->getLeaveTypeService();

            $leaveTypes     = $leaveTypeService->getLeaveTypeList();
            $leaveTypeList  = array();

            foreach($leaveTypes as $leaveType) {
                $balance = $leaveEntitlementService->getLeaveBalance($employee->getEmpNumber(), $leaveType->getId());

                if($balance->getEntitled() > 0) {
                    array_push($leaveTypeList, $leaveType);
                }
            }
            return $leaveTypeList;
        } catch(Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

    /**
     *
     * @param date $leaveStartDate
     * @param date $leaveEndDate
     * @param int $empId
     * @return Leave List
     * @todo Parameter list is too long. Refactor to use LeaveParameterObject
     */
    public function getOverlappingLeave($leaveStartDate, $leaveEndDate ,$empId, $startTime = '00:00', $endTime='59:00', $hoursPerday = '8') {

        return $this->getLeaveRequestDao()->getOverlappingLeave($leaveStartDate, $leaveEndDate ,$empId,  $startTime, $endTime, $hoursPerday);

    }

    /**
     *
     * @param LeaveType $leaveType
     * @return boolean
     */
    public function isApplyToMoreThanCurrent(LeaveType $leaveType){
		try{
			$leaveRuleEligibilityProcessor	=	new LeaveRuleEligibilityProcessor();
			return $leaveRuleEligibilityProcessor->allowApplyToMoreThanCurrent($leaveType);

		}catch( Exception $e){
			throw new LeaveServiceException($e->getMessage());
		}
	}

    /**
     *
     * @param $empId
     * @param $leaveTypeId
     * @return int
     */
    public function getNumOfLeave($empId, $leaveTypeId) {

        return $this->getLeaveRequestDao()->getNumOfLeave($empId, $leaveTypeId);

    }

    /**
     *
     * @param $empId
     * @param $leaveTypeId 
     * @param $$leavePeriodId
     * @return int
     */
    public function getNumOfAvaliableLeave($empId, $leaveTypeId, $leavePeriodId = null) {
        
        return $this->getLeaveRequestDao()->getNumOfAvaliableLeave($empId, $leaveTypeId, $leavePeriodId);
        
    }

    /**
     *
     * @param $empId
     * @param $leaveTypeId
     * @return bool
     */
    public function isEmployeeHavingLeaveBalance( $empId, $leaveTypeId ,$leaveRequest,$applyDays) {
        try {
            $leaveEntitlementService = $this->getLeaveEntitlementService();
            $entitledDays	=	$leaveEntitlementService->getEmployeeLeaveEntitlementDays($empId, $leaveTypeId,$leaveRequest->getLeavePeriodId());
            $leaveDays		=	$this->getLeaveRequestDao()->getNumOfAvaliableLeave($empId, $leaveTypeId);

            $leaveEntitlement = $leaveEntitlementService->readEmployeeLeaveEntitlement($empId, $leaveTypeId, $leaveRequest->getLeavePeriodId());
            $leaveBoughtForward = 0;
            if($leaveEntitlement instanceof EmployeeLeaveEntitlement) {
                $leaveBoughtForward = $leaveEntitlement->getLeaveBroughtForward();
            }

            $leaveBalance = $leaveEntitlementService->getLeaveBalance(
                    $empId, $leaveTypeId,
                    $leaveRequest->getLeavePeriodId());

            $entitledDays += $leaveBoughtForward;

            if($entitledDays == 0)
                throw new Exception('Leave Entitlements Not Allocated',102);

            //this is for border period leave apply - days splitting
            $leavePeriodService = $this->getLeavePeriodService();

            //this would either create or returns the next leave period
            $currentLeavePeriod     = $leavePeriodService->getLeavePeriod(strtotime($leaveRequest->getDateApplied()));
            $leaveAppliedEndDateTimeStamp = strtotime("+" . $applyDays . " day", strtotime($leaveRequest->getDateApplied()));
            $nextLeavePeriod        = $leavePeriodService->createNextLeavePeriod(date("Y-m-d", $leaveAppliedEndDateTimeStamp));
            $currentPeriodStartDate = explode("-", $currentLeavePeriod->getStartDate());
            $nextYearLeaveBalance   = 0;

            if($nextLeavePeriod instanceof LeavePeriod) {
                $nextYearLeaveBalance = $leaveEntitlementService->getLeaveBalance(
                        $empId, $leaveTypeId,
                        $nextLeavePeriod->getLeavePeriodId());
                //this is to notify users are applying to the same leave period
                $nextPeriodStartDate    = explode("-", $nextLeavePeriod->getStartDate());
                if($nextPeriodStartDate[0] == $currentPeriodStartDate[0]) {
                    $nextLeavePeriod        = null;
                    $nextYearLeaveBalance   = 0;
                }
            }

            //this is only applicable if user applies leave during current leave period
            if(strtotime($currentLeavePeriod->getStartDate()) < strtotime($leaveRequest->getDateApplied()) &&
                    strtotime($currentLeavePeriod->getEndDate()) > $leaveAppliedEndDateTimeStamp) {
                if($leaveBalance < $applyDays) {
                    throw new Exception('Leave Balance Exceeded',102);
                }
            }

            //this is to verify whether leave applied within border period
            if($nextLeavePeriod instanceof LeavePeriod && strtotime($currentLeavePeriod->getStartDate()) < strtotime($leaveRequest->getDateApplied()) &&
                    strtotime($nextLeavePeriod->getEndDate()) > $leaveAppliedEndDateTimeStamp) {

                $endDateTimeStamp = strtotime($leavePeriodService->getCurrentLeavePeriod()->getEndDate());
                $borderDays = date("d", ($endDateTimeStamp - strtotime($leaveRequest->getDateApplied())));
                if($borderDays > $leaveBalance || $nextYearLeaveBalance < ($applyDays - $borderDays)) {
                    throw new Exception("Leave Balance Exceeded", 102);
                }
            }

            return true ;

        }catch( Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

    public function isLeaveRequestWithinLeaveBalance($employeeId, $leaveTypeId, $leaveList) {

        $currentLeavePeriod = $this->getLeavePeriodService()->getCurrentLeavePeriod();
        $currentLeavePeriodEndDate = $currentLeavePeriod->getEndDate();
        $currentLeavePeriodEndDateTimeStamp = strtotime($currentLeavePeriodEndDate);

        $leaveEntitlementService = $this->getLeaveEntitlementService();

        $leaveLengthOnCurrentLeavePeriod = 0;
        $leaveLengthOnNextLeavePeriod = 0;

        $canApplyForCurrentLeavePeriod = true;
        $canApplyForNextLeavePeriod = true;

        foreach ($leaveList as $leave) {

            if (strtotime($leave->getLeaveDate()) <= $currentLeavePeriodEndDateTimeStamp) {

                $leaveLengthOnCurrentLeavePeriod += $leave->getLeaveLengthDays();

            } else {

                $leaveLengthOnNextLeavePeriod += $leave->getLeaveLengthDays();

            }

        }

        if ($leaveLengthOnCurrentLeavePeriod > 0) {

            $currentLeaveBalance = $leaveEntitlementService->getLeaveBalance($employeeId, $leaveTypeId, $currentLeavePeriod->getLeavePeriodId());

            if ($leaveLengthOnCurrentLeavePeriod > $currentLeaveBalance) {

                $canApplyForCurrentLeavePeriod = false;

            }

        }

        if ($leaveLengthOnNextLeavePeriod > 0) {

            $nextLeavePeriod = $this->getLeavePeriodService()->getNextLeavePeriodByCurrentEndDate($currentLeavePeriodEndDate);

            if ($nextLeavePeriod instanceof LeavePeriod) {

                $nextLeaveBalance = $leaveEntitlementService->getLeaveBalance($employeeId, $leaveTypeId, $nextLeavePeriod->getLeavePeriodId());

                if ($leaveLengthOnNextLeavePeriod > $nextLeaveBalance) {

                    $canApplyForNextLeavePeriod = false;

                }

            } else {

                $canApplyForNextLeavePeriod = false;

            }

        }

        if ($canApplyForCurrentLeavePeriod && $canApplyForNextLeavePeriod) {
            return true;
        } else {
            return false;
        }

    }

    /**
     *
     * @param ParameterObject $searchParameters
     * @param array $statuses
     * @return array
     */
    public function searchLeaveRequests($searchParameters, $page = 1, $isCSVPDFExport = false, $isMyLeaveList = false) {
        $result = $this->getLeaveRequestDao()->searchLeaveRequests($searchParameters, $page, $isCSVPDFExport, $isMyLeaveList);
        return $result;

    }

    /**
     * Get Leave Request Status
     * @param $day
     * @return unknown_type
     */
    public function getLeaveRequestStatus( $day ) {
        try {
            $holidayService = $this->getHolidayService();
            $holiday = $holidayService->readHolidayByDate($day);
            if ($holiday != null) {
                return Leave::LEAVE_STATUS_LEAVE_HOLIDAY;
            }

            return Leave::LEAVE_STATUS_LEAVE_PENDING_APPROVAL;

        } catch (Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

    /**
     *
     * @param int $leaveRequestId
     * @return array
     */
    public function searchLeave($leaveRequestId) {

        return $this->getLeaveRequestDao()->fetchLeave($leaveRequestId);

    }

    /**
     *
     * @param int $leaveId
     * @return array
     */
    public function readLeave($leaveId) {

        return $this->getLeaveRequestDao()->readLeave($leaveId);

    }

    public function saveLeave(Leave $leave) {
        return $this->getLeaveRequestDao()->saveLeave($leave);
    }

    /**
     * @param int $leaveRequestId
     */
    public function fetchLeaveRequest($leaveRequestId) {

        return $this->getLeaveRequestDao()->fetchLeaveRequest($leaveRequestId);

    }

    /**
     * Modify Over lap leaves
     * @param LeaveRequest $leaveRequest
     * @param $leaveList
     * @return unknown_type
     */
    public function modifyOverlapLeaveRequest(LeaveRequest $leaveRequest , $leaveList ) {

        return $this->getLeaveRequestDao()->modifyOverlapLeaveRequest($leaveRequest , $leaveList);

    }

    /**
     *
     * @param LeavePeriod $leavePeriod
     * @return boolean
     */
    public function adjustLeavePeriodOverlapLeaves(LeavePeriod $leavePeriod) {

        $overlapleaveList =	$this->getLeaveRequestDao()->getLeavePeriodOverlapLeaves($leavePeriod);

        if (count($overlapleaveList) > 0) {

            foreach($overlapleaveList as $leave) {

                $leaveRequest	=	$leave->getLeaveRequest();
                $leaveList		=	$this->getLeaveRequestDao()->fetchLeave($leaveRequest->getLeaveRequestId());
                $this->getLeaveRequestDao()->modifyOverlapLeaveRequest($leaveRequest,$leaveList,$leavePeriod);

            }

        }

    }

    /**
     *
     * @param array $changes
     * @param string $changeType
     * @return boolean
     */
    public function changeLeaveStatus($changes, $changeType, $changeComments = null, $changedByUserType = null, $changedUserId = null) {
        if(is_array($changes)) {
            $approvalIds = array_keys(array_filter($changes, array($this, '_filterApprovals')));
            $rejectionIds = array_keys(array_filter($changes, array($this, '_filterRejections')));
            $cancellationIds = array_keys(array_filter($changes, array($this, '_filterCancellations')));

            if ($changeType == 'change_leave_request') {
                
                foreach ($approvalIds as $leaveRequestId) {
                    $approvals = $this->searchLeave($leaveRequestId);
                    $this->_changeLeaveStatus($approvals, Leave::LEAVE_STATUS_LEAVE_APPROVED, $changeComments[$leaveRequestId]);

                    $this->_notifyLeaveStatusChange(LeaveEvents::LEAVE_APPROVE, $approvals, 
                            $changedByUserType, $changedUserId, 'request');
                }

                foreach ($rejectionIds as $leaveRequestId) {
                    $rejections = $this->searchLeave($leaveRequestId);
                    $this->_changeLeaveStatus($rejections, Leave::LEAVE_STATUS_LEAVE_REJECTED, $changeComments[$leaveRequestId]);
                    $this->_notifyLeaveStatusChange(LeaveEvents::LEAVE_REJECT, $rejections, 
                            $changedByUserType, $changedUserId, 'request');
                }

                foreach ($cancellationIds as $leaveRequestId) {
                    $cancellations = $this->searchLeave($leaveRequestId);

                    $this->_changeLeaveStatus($cancellations, Leave::LEAVE_STATUS_LEAVE_CANCELLED);
                    
                    $this->_notifyLeaveStatusChange(LeaveEvents::LEAVE_CANCEL, $cancellations, 
                            $changedByUserType, $changedUserId, 'request');                  
                }

            } elseif ($changeType == 'change_leave') {

                $actionTypes = 0;
                
                $approvals = array();
                foreach ($approvalIds as $leaveId) {
                    $approvals[] = $this->getLeaveRequestDao()->getLeaveById($leaveId);
                }
                if (count($approvals) > 0) {
                    $actionTypes++;
                    $this->_changeLeaveStatus($approvals, Leave::LEAVE_STATUS_LEAVE_APPROVED, $changeComments);                    
                }
                
                $rejections = array();
                foreach ($rejectionIds as $leaveId) {
                    $rejections[] = $this->getLeaveRequestDao()->getLeaveById($leaveId);
                }
                
                if (count($rejections) > 0) {
                    $actionTypes++;
                    $this->_changeLeaveStatus($rejections, Leave::LEAVE_STATUS_LEAVE_REJECTED, $changeComments);
                }
                $cancellations = array();
                foreach ($cancellationIds as $leaveId) {
                    $cancellations[] = $this->getLeaveRequestDao()->getLeaveById($leaveId);
                }
                
                if (count($cancellations) > 0) {
                    $actionTypes++;
                    $this->_changeLeaveStatus($cancellations, Leave::LEAVE_STATUS_LEAVE_CANCELLED);
                }
                
                // Notifications
                if ($actionTypes > 0) {
                    if ($actionTypes == 1) {
                        if (count($approvals) > 0) {
                            $this->_notifyLeaveStatusChange(LeaveEvents::LEAVE_APPROVE, $approvals, 
                                    $changedByUserType, $changedUserId, 'multiple');                                            
                        } else if (count($rejections) > 0) {
                            $this->_notifyLeaveStatusChange(LeaveEvents::LEAVE_REJECT, $rejections, 
                                    $changedByUserType, $changedUserId, 'multiple');                                                
                        } else if (count($cancellations) > 0) {
                            $this->_notifyLeaveStatusChange(LeaveEvents::LEAVE_CANCEL, $cancellations, 
                                    $changedByUserType, $changedUserId, 'multiple');                                            
                        }
                        
                    } else {
                        $changes = array();
                        $allDays = array();
                        
                        if (count($approvals) > 0) {
                            $changes[LeaveEvents::LEAVE_APPROVE] = $approvals;
                            $allDays = array_merge($allDays, $approvals);
                        }
                        
                        if (count($rejections) > 0) {
                            $changes[LeaveEvents::LEAVE_REJECT] = $rejections;
                            $allDays = array_merge($allDays + $rejections);
                        }
                        
                        if (count($cancellations) > 0) {
                            $changes[LeaveEvents::LEAVE_CANCEL] = $cancellations;
                            $allDays = array_merge($allDays + $cancellations);
                        }
                        
                        $this->_notifyLeaveMultiStatusChange($allDays, $changes, 
                                    $changedByUserType, $changedUserId, 'multiple');                         
                        
                    }
                }
                //$allChangedItems = count($approvals) + count($rejections) + count()
            } else {
                throw new LeaveServiceException('Wrong change type passed');
            }
        }else {
            throw new LeaveServiceException('Empty changes list');
        }

    }

    protected function _changeLeaveStatus($leaveList, $newState, $comments = null) {
        $dao = $this->getLeaveRequestDao();
        
        foreach ($leaveList as $leave) {

            $entitlementChanges = array();
            
            $removeLinkedEntitlements = (($newState == Leave::LEAVE_STATUS_LEAVE_CANCELLED) || 
                    ($newState == Leave::LEAVE_STATUS_LEAVE_REJECTED));
            

            $strategy = $this->getLeaveEntitlementService()->getLeaveEntitlementStrategy();     
            
            if ($removeLinkedEntitlements) {                
                $entitlementChanges = $strategy->handleLeaveCancel($leave);
            }
            
            $leave->setStatus($newState);
            
            if (!is_null($comments)) {
                if (is_array($comments)) {
                    $comment = isset($comments[$leave->getId()]) ? $comments[$leave->getId()] : '';
                } else {
                    $comment = $comments;
                }
                $leave->setComments($comment);
            }
            
            $dao->changeLeaveStatus($leave, $entitlementChanges, $removeLinkedEntitlements);
        }                
    }
    
    private function _notifyLeaveStatusChange($eventType, $leaveList, $performerType, $performerId, $requestType) {
        $eventData = array('days' => $leaveList, 
                           'performerType' => $performerType, 
                           'empNumber' => $performerId, 
                           'requestType' => $requestType);
        $this->getDispatcher()->notify(new sfEvent($this, $eventType, $eventData));   
    }
    
    private function _notifyLeaveMultiStatusChange($allDays, $leaveList, $performerType, $performerId, $requestType) {
        $eventData = array('days' => $allDays,
                           'changes' => $leaveList, 
                           'performerType' => $performerType, 
                           'empNumber' => $performerId, 
                           'requestType' => $requestType);
        $this->getDispatcher()->notify(new sfEvent($this, LeaveEvents::LEAVE_CHANGE, $eventData));   
    }    

    public function getScheduledLeavesSum($employeeId, $leaveTypeId, $leavePeriodId) {

        return $this->getLeaveRequestDao()->getScheduledLeavesSum($employeeId, $leaveTypeId, $leavePeriodId);

    }

    public function getTakenLeaveSum($employeeId, $leaveTypeId, $leavePeriodId) {

        return $this->getLeaveRequestDao()->getTakenLeaveSum($employeeId, $leaveTypeId, $leavePeriodId);

    }
    
    public function getLeaveRequestActions($request, $loggedInEmpNumber) {
        $actions = array();

        if (!$request->isStatusDiffer()) {
            
            $includeRoles = array();
            $excludeRoles = array();

            if ($request->getEmpNumber() == $loggedInEmpNumber) {
                $includeRoles = array('ESS');
            }            
            
            $leaveTypeDeleted = $request->getLeaveType()->getDeleted();
            
            $status = Leave::getTextForLeaveStatus($request->getLeaveStatusId());
                    
            if ($leaveTypeDeleted) {
                $status = Leave::LEAVE_STATUS_LEAVE_TYPE_DELETED_TEXT . ' ' . $status;
            }

            $actionNames = $this->getUserRoleManager()->getAllowedActions(WorkflowStateMachine::FLOW_LEAVE, 
                    $status, $excludeRoles, $includeRoles);

            foreach ($actionNames as $name) {
                $actions[$name] = ucfirst(strtolower($name));
            }         
        }
        
        return $actions;
    }
    
    public function getLeaveActions($leave, $loggedInEmpNumber) {
        $actions = array();
        
        $includeRoles = array();
        $excludeRoles = array();

        if ($leave->getEmpNumber() == $loggedInEmpNumber) {
            $includeRoles = array('ESS');
        }
        
        $status = $leave->getTextLeaveStatus();
        
        $leaveTypeDeleted = $leave->getLeaveType()->getDeleted();

        if ($leaveTypeDeleted) {
            $status = Leave::LEAVE_STATUS_LEAVE_TYPE_DELETED_TEXT . ' ' . $status;
        }
            
        $actionNames = $this->getUserRoleManager()->getAllowedActions(WorkflowStateMachine::FLOW_LEAVE, 
                $status, $excludeRoles, $includeRoles);

        foreach ($actionNames as $name) {
            $actions[$name] = ucfirst(strtolower($name));
        }    
        
        return $actions;
    }

    /**
     *
     * @param string $element
     * @return boolean
     */
    private function _filterApprovals($element) {
        return ($element == 'APPROVE');
    }

    /**
     *
     * @param unknown_type $element
     * @return boolean
     */
    private function _filterRejections($element) {
        return ($element == 'REJECT');
    }

    /**
     *
     * @param unknown_type $element
     * @return boolean
     */
    private function _filterCancellations($element) {
        return ($element == 'CANCEL');
    }
    

    /**
     *
     * @param type $employeeId
     * @param type $date
     * @return double
     */
    public function getTotalLeaveDuration($employeeId, $date){
        return $this->getLeaveRequestDao()->getTotalLeaveDuration($employeeId, $date);
    }

    public function getLeaveById($leaveId) {
        return $this->getLeaveRequestDao()->getLeaveById($leaveId);
    }
     /**
     *
     * @param ParameterObject $searchParameters
     * @param array $statuses
     * @return array
     */
    public function getLeaveRequestSearchResultAsArray($searchParameters) {
        return $this->getLeaveRequestDao()->getLeaveRequestSearchResultAsArray($searchParameters);
    }
    
     /**
     *
     * @param ParameterObject $searchParameters
     * @param array $statuses
     * @return array
     */
    public function getDetailedLeaveRequestSearchResultAsArray($searchParameters) {
        return $this->getLeaveRequestDao()->getDetailedLeaveRequestSearchResultAsArray($searchParameters);
    }

    public function markApprovedLeaveAsTaken() {
        return $this->getLeaveRequestDao()->markApprovedLeaveAsTaken();
    }
}
