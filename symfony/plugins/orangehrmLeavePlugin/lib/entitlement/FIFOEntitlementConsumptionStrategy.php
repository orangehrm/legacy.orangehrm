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
            
                $entitlements = $this->getLeaveEntitlementService()->getValidLeaveEntitlements($empNumber, $leaveType, $fromDate, $toDate, 'to_date', 'ASC');            
                        
                reset($leaveDates);
                $leaveNdx = 0;
                $getNext = true;
                $entitlementsOk = false;
                
                $entitlement = array_shift($entitlements);
                
                $tmpArray = array();
                $skipTemp = false;
                
                while (!is_null($entitlement)) {
                    $availableDays = $entitlement->getAvailableDays();

                    if ($availableDays > 0) {                       
                        
                        if ($getNext) {
                            if ($leaveNdx < $numDates) {                                
                                $leaveDate = $leaveDates[$leaveNdx++];                            
                                $leaveLength = $leaveDate->getLengthDays();      
                            } else {
                                $entitlementsOk = true;
                                break;
                            }
                        }
//                        var_dump($leaveDate->getDate());
//                        var_dump($availableDays . ', ' . $leaveLength);
                        $getNext = false;
                        //var_dump('xx' . $leaveDate->getDate());
                        if ($leaveLength <= 0) {
                            //var_dump("leaveLength = 0");
                            $getNext = true;
                            $skipTemp = false;
                            if (count($tmpArray) > 0) {
                                array_unshift($tmpArray, $entitlement);
                            } else {
                                array_unshift($entitlements, $entitlement);
                            }                            
                        } else if (!$entitlement->withinPeriod($leaveDate->getDate())) {

                            //var_dump("H");
                            array_push($tmpArray, $entitlement);
                            
                            $skipTemp = true;
                            //break;

                        } else if ($leaveLength <= $availableDays) {
                            $entitlement->days_used += $leaveLength;
                            $availableDays -= $leaveLength;
                            $leaveDate->setEntitlementId($entitlement->id);
                            $getNext = true;                            
                            
                            $skipTemp = false;
                            if ($leaveNdx >= $numDates) {  
                                 $entitlementsOk = true;
                            } 
                            
                            if ($availableDays > 0) {
                                if (count($tmpArray) > 0) {
                                    array_unshift($tmpArray, $entitlement);
                                } else {
                                    array_unshift($entitlements, $entitlement);
                                }
                            }
                            
                            //var_dump("WORKED: " . $entitlement->id . ', ' . $entitlement->getAvailableDays());
                            //var_dump("leaveNdx=" . $leaveNdx . ', NumDates=' . $numDates);
                        } else {
                            //var_dump("LESS");
                            $entitlement->days_used = $entitlement->no_of_days;
                            $leaveLength -= $availableDays;
                            $availableDays = 0;
                        }
                    }

//var_dump("1!!");
                    if ($entitlementsOk) {
                        //var_dump("BREAK");
                        break;
                    }
                    
                    //var_dump($skipTemp); 
                    //var_dump(count($tmpArray));
                    //var_dump(count($entitlements));
                    //die;
                    
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

        if ($entitlementsOk) {
            //var_dump("OOOKKK");
            return $leaveDates;
        } else {
            //var_dump("FALSE____");
            return false;
        }        
    }
}
