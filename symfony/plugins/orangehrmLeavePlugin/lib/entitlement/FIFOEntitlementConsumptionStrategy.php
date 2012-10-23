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
 * Description of FIFOEntitlementConsumptionStrategy
 */
class FIFOEntitlementConsumptionStrategy implements EntitlementConsumptionStrategy {
    
    protected $leaveEntitlementService;
    
    public function getLeaveEntitlementService() {
        if (empty($this->leaveEntitlementService)) {
            $this->leaveEntitlementService = new LeaveEntitlementService();
        }
        return $this->leaveEntitlementService;
    }

    public function setLeaveEntitlementService($leaveEntitlementService) {
        $this->leaveEntitlementService = $leaveEntitlementService;
    }

    
    /**
     * Get available entitlements for given leave parameters
     * 
     * Returns an array of entitlement ids with no_of_days as the value.
     * eg:
     * array( 11 => 2.0
     *        14 => 1.5)
     * 
     * If one entitlement satisfy the leave request, only one entitlement will be returned in 
     * the array
     * 
     * @param $empNumber int Employee Number
     * @param $leaveType int LeaveType
     * @param $leaveDates Array Array of LeaveDate => Length (days)
     * @return Array of entitlement id => length (days) 
     */
    public function getAvailableEntitlements($empNumber, $leaveType, $leaveDates) {
        $availableEntitlements = array();
        
        if (count($leaveDates) > 0) {
            $keys = array_keys($leaveDates);
            $fromDate = $keys[0];
            $toDate = end($keys);
            
            $leaveLength = 0;
            foreach ($leaveDates as $leaveDate => $length) {
                $leaveLength += $length;
            }
            
            $entitlements = $this->getLeaveEntitlementService()->getValidLeaveEntitlements($empNumber, $leaveType, $fromDate, $toDate, 'from_date', 'ASC');
            
            foreach ($entitlements as $entitlement) {
                $availableDays = $entitlement->getNoOfDays();
                
                if ($availableDays > 0) {
                    if ($availableDays >= $leaveLength) {
                        $availableEntitlements[$entitlement->getId()] = $leaveLength;
                        break;
                    } else {
                        $availableEntitlements[$entitlement->getId()] = $availableDays;
                        $leaveLength -= $availableDays;
                    }
                }
            }
        }
        
        return $availableEntitlements;
    }
}
