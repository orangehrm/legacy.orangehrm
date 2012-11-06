<?php

/**
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

/**
 * LeaveEntitlement service
 */
class LeaveEntitlementService extends BaseService {

    protected $leaveConfigService;
    protected $leaveEntitlementDao;
    protected $leaveEntitlementStrategy;
    
    public function getLeaveEntitlementStrategy() {
        if (!isset($this->leaveEntitlementStrategy)) {
            
            $strategyClass = $this->getLeaveConfigService()->getLeaveEntitlementConsumptionStrategy();            
            $this->leaveEntitlementStrategy = new $strategyClass;
        }
        
        return $this->leaveEntitlementStrategy;
    }

    public function getLeaveConfigService() {
        if (!($this->leaveConfigService instanceof LeaveConfigurationService)) {
            $this->leaveConfigService = new LeaveConfigurationService();
        }        
        return $this->leaveConfigService;
    }

    public function setLeaveConfigService($leaveConfigService) {
        $this->leaveConfigService = $leaveConfigService;
    }

    
    public function getLeaveEntitlementDao() {
        if (!($this->leaveEntitlementDao instanceof LeaveEntitlementDao)) {
            $this->leaveEntitlementDao = new LeaveEntitlementDao();
        }
        return $this->leaveEntitlementDao;
    }

    public function setLeaveEntitlementDao(LeaveEntitlementDao $leaveEntitlementDao) {
        $this->leaveEntitlementDao = $leaveEntitlementDao;
    }
    
    public function searchLeaveEntitlements(LeaveEntitlementSearchParameterHolder $searchParameters) {
        return $this->getLeaveEntitlementDao()->searchLeaveEntitlements($searchParameters);
    }
    
    public function saveLeaveEntitlement(LeaveEntitlement $leaveEntitlement) {
        return $this->getLeaveEntitlementDao()->saveLeaveEntitlement($leaveEntitlement);
    }
    
    public function deleteLeaveEntitlements($ids) {
        return $this->getLeaveEntitlementDao()->deleteLeaveEntitlements($ids);
    }    
    
    public function getLeaveEntitlement($id) {
        return $this->getLeaveEntitlementDao()->getLeaveEntitlement($id);
    }    
    
    public function bulkAssignLeaveEntitlements($employeeNumbers, LeaveEntitlement $leaveEntitlement) {
        return $this->getLeaveEntitlementDao()->bulkAssignLeaveEntitlements($employeeNumbers, $leaveEntitlement);
    }
    
    public function getAvailableEntitlements(LeaveParameterObject $leaveParameterObject) {
        return $this->getLeaveEntitlementStrategy()->getAvailableEntitlements($leaveParameterObject);
    }
    
    public function getValidLeaveEntitlements($empNumber, $leaveTypeId, $fromDate, $toDate, $orderField, $order) {
        return $this->getLeaveEntitlementDao()->getValidLeaveEntitlements($empNumber, $leaveTypeId, $fromDate, $toDate, $orderField, $order);
    }
    
    public function getLinkedLeaveRequests($entitlementIds, $statuses) {
        return $this->getLeaveEntitlementDao()->getLinkedLeaveRequests($entitlementIds, $statuses);
    }    
    
    public function getLeaveBalance($empNumber, $leaveTypeId, $asAtDate = NULL, $date = NULL) {
        if (empty($asAtDate)) {
            $asAtDate = date('Y-m-d', time());
        }

        return $this->getLeaveEntitlementDao()->getLeaveBalance($empNumber, $leaveTypeId, $asAtDate, $date);
    }

}
