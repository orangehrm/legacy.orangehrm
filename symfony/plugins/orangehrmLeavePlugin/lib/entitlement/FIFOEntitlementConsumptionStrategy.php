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
     * Returns an array with the following structure:
     * eg:
     * array('current' => array(
     *          '2012-01-01' => array(1 => 1)
     *          '2012-01-02' => array(4 => 1)),
     *       'change' => array(
     *          12 => array(3 => 1), / leave_id => array(entitlement_id => length) /
     *          13 => array(4 => 0.5))
     * )
     * 
     * If unable to assign the requested leave, will return false
     * 
     * Here 'current' contains assignments for the currently requested leave dates
     * 'change' contains assignments for existing leave requests that need to change.
     * 
     * @param $empNumber int Employee Number
     * @param $leaveType int LeaveType
     * @param $leaveDates Array Array of LeaveDate => Length (days)
     * @return Array or false As described above
     */    
    public function getAvailableEntitlements($empNumber, $leaveType, $leaveDates) {

        $result = false;
        $current = array();
        $change = array();
        
        $numDates = count($leaveDates);
        $leaveLength = 0;

        if ($numDates > 0) {

            $fromDate = NULL;
            $toDate = NULL;            

            foreach ($leaveDates as $leaveDate) {
                $length = $leaveDate->getLengthDays();
                if ($length > 0) {
                    if (is_null($fromDate)) {
                        $fromDate = $leaveDate->getDate();
                    }
                    $toDate = $leaveDate->getDate();
                }
                $leaveLength += $length;
            }

            $entitlementsOk = true;

            if (!is_null($fromDate)) {

                
                $newentitlements = $this->getLeaveEntitlementService()->getValidLeaveEntitlements($empNumber, $leaveType->getId(), $fromDate, $toDate, 'to_date', 'ASC');            
                
                // TODO Get currently assigned leave dates and add to $leaveDates
                $entitlements = array();
                $entitlementIds = array();
                foreach($newentitlements as $entitlement) {
                    $entitlementIds[] = $entitlement->getId();
                    $entitlements[] = $entitlement;
                }

                $statuses = array(
                    Leave::LEAVE_STATUS_LEAVE_PENDING_APPROVAL,
                    Leave::LEAVE_STATUS_LEAVE_APPROVED);
                
                $otherLeaveDates = $this->getLeaveEntitlementService()->getLinkedLeaveRequests($entitlementIds, $statuses);

                $leaveDates = $this->mergeLeaveDates($leaveDates, $otherLeaveDates);                
                $numDates = count($leaveDates);
                
                reset($leaveDates);
                $leaveNdx = 0;
                $getNextDate = true;
                $entitlementsOk = false;
                
                $entitlement = array_shift($entitlements);
                $tmpArray = array();
                $skipTemp = false;
                
                while (!is_null($entitlement)) {
                    
                    $availableDays = $entitlement->getAvailableDays();

                    if ($availableDays > 0) {                       
                        
                        if ($getNextDate) {
                            $getNextDate = false;
                            if ($leaveNdx < $numDates) {                                
                                $leaveDate = $leaveDates[$leaveNdx++];                            
                                $leaveLength = $leaveDate->getLengthDays();  

                            } else {
                                $entitlementsOk = true;
                                break;
                            }
                        }                        

                        //echo('leaveLength = ' . $leaveLength . "\n");
                        if ($leaveLength <= 0) {
                            //var_dump("leaveLength = 0");
                            $getNextDate = true;
                            $skipTemp = false;                           
                        } else if (!$entitlement->withinPeriod($leaveDate->getDate())) {

                            array_push($tmpArray, $entitlement);                            
                            $skipTemp = true;

                        } else if ($leaveLength <= $availableDays) {
                            $entitlement->days_used += $leaveLength;
                            $availableDays -= $leaveLength;
                            
                            $leaveId = $leaveDate->getId();

                            if (empty($leaveId)) {
                                if (!isset($current[$leaveDate->getDate()])) {
                                    $current[$leaveDate->getDate()] = array();
                                }
                                $current[$leaveDate->getDate()][$entitlement->id] = $leaveLength;
                            } else {
                                if (!isset($change[$leaveDate->getId()])) {
                                    $change[$leaveDate->getId()] = array();
                                }
                                $change[$leaveDate->getId()][$entitlement->id] = $leaveLength;                                
                            }
                            $getNextDate = true;                            
                            
                            $skipTemp = false;
                            if ($leaveNdx >= $numDates) {  
                                 $entitlementsOk = true;
                            } 
                            
                            array_unshift($entitlements, $entitlement);
                            
                            //var_dump("WORKED: " . $entitlement->id . ', ' . $entitlement->getAvailableDays());
                            //var_dump("leaveNdx=" . $leaveNdx . ', NumDates=' . $numDates);
                        } else {
                            //var_dump("LESS");
                            $entitlement->days_used = $entitlement->no_of_days;
                            $leaveLength -= $availableDays;
                            
                            $leaveId = $leaveDate->getId();

                            if (empty($leaveId)) {                            
                                if (!isset($current[$leaveDate->getDate()])) {
                                    $current[$leaveDate->getDate()] = array();
                                }
                                $current[$leaveDate->getDate()][$entitlement->id] = $availableDays;                                
                            } else {
                                if (!isset($change[$leaveDate->getId()])) {
                                    $change[$leaveDate->getId()] = array();
                                }
                                $change[$leaveDate->getId()][$entitlement->id] = $availableDays;                                 
                            }

                            
                            $availableDays = 0;
                            
                            $getNextDate = false;
                            //echo('LESS leaveLength = ' . $leaveLength . "\n");
                        }
                    }

                    if ($entitlementsOk) {
                        //var_dump("BREAK");
                        break;
                    }                   
                    
                    if (!$skipTemp && (count($tmpArray) > 0)) {
                        $entitlement = array_shift($tmpArray);
                        //var_dump("T");
                    } else {
                        //var_dump("E");
                        $entitlement = array_shift($entitlements);
                    }
                }
            }
        }
        
        if ($entitlementsOk && (count($change) > 0 || count($current) > 0)) {
            $result = array('current' => $current, 'change' => $change);
        }
        
        return $result;
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
    public function getAvailableEntitlementsOld($empNumber, $leaveType, $leaveDates) {
                
        $numDates = count($leaveDates);
        
        if ($numDates > 0) {
            
            $fromDate = NULL;
            $toDate = NULL;
            $leaveLength = 0;
            
            foreach ($leaveDates as $leaveDate) {
                $length = $leaveDate->getLengthDays();
                if ($length > 0) {
                    if (is_null($fromDate)) {
                        $fromDate = $leaveDate->getDate();
                    }
                    $toDate = $leaveDate->getDate();
                }                
                $leaveLength += $length;
            }
            
            $entitlementsOk = true;
            
            if (!is_null($fromDate)) {
            

            }
        }

        if ($entitlementsOk) {
            //var_dump("OOOKKK");
            return $leaveDates;
        } else {
            //var_dump("FALSE____");
            return false;
        }        
    }
    
    protected function mergeLeaveDates($leaveDates, $otherLeaveDates) {
                
        $result = array();
        
        foreach ($leaveDates as $date) {
            $key = strtotime($date->getDate());
            
            if (isset($result[$key])) {
                $result[$key][] = $date;
            } else {
                $result[$key] = array($date);
            }

        }
        
        if (is_array($otherLeaveDates)) {
            foreach ($otherLeaveDates as $date) {
                $key = strtotime($date->getDate());

                if (isset($result[$key])) {
                    $result[$key][] = $date;
                } else {
                    $result[$key] = array($date);
                }

            }            
        }
        
        ksort($result);
        
        $sortedDates = array();
        
        foreach ($result as $oneItem) {
            foreach($oneItem as $item) {
                $sortedDates[] = $item;
            }
        }
        
        return $sortedDates;        
        
    }
}
