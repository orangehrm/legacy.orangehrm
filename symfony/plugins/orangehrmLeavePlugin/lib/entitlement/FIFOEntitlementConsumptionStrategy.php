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
    public function getAvailableEntitlements($empNumber, $leaveType, $leaveDates, $allowNoEntitlements = false) {

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

                
                $newentitlements = $this->getLeaveEntitlementService()->getValidLeaveEntitlements($empNumber, $leaveType, $fromDate, $toDate, 'to_date', 'ASC');            
                
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
        
        if ($allowNoEntitlements || ($entitlementsOk && (count($change) > 0 || count($current) > 0))) {
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

    public function handleLeaveCreate($empNumber, $leaveType, $leaveDates, $allowNoEntitlements = false) {
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


                $newentitlements = $this->getLeaveEntitlementService()->getValidLeaveEntitlements($empNumber, $leaveType, $fromDate, $toDate, 'from_date', 'ASC');            

                // TODO Get currently assigned leave dates and add to $leaveDates
                $entitlements = array();
                foreach($newentitlements as $entitlement) {
                    $entitlements[] = $entitlement;
                }

                reset($leaveDates);

                $getNextDate = true;
                $entitlementsOk = false;
                $leaveDate = null;
                $leaveLength = 0;
                
                $leaveWithoutEntitlement = array();

                $entitlement = array_shift($entitlements); 
                
                if (!is_null($entitlement)) {
                    $availableDays = $entitlement->getAvailableDays();         
                }
                
                while (!is_null($entitlement)) {

                    if ($availableDays > 0) {
                        
                        if ($getNextDate) {
                            $leaveDate = array_shift($leaveDates);
                            
                            if (is_null($leaveDate)) {
                                
                                $entitlementsOk = empty($leaveWithoutEntitlement);
                                $leaveLength = 0;
                                break;
                            } else {
                                $leaveLength = $leaveDate->getLengthDays();
                                $getNextDate = false;
                            }
                        }
                        if ($leaveLength <= 0) {
                            $getNextDate = true;                    
                        } else if (!$entitlement->withinPeriod($leaveDate->getDate())) {

                            if (strtotime($leaveDate->getDate()) < strtotime($entitlement->getFromDate())) {
                                $getNextDate = true;
                                $leaveWithoutEntitlement[] = $leaveDate;
                            } else if (strtotime($leaveDate->getDate()) > strtotime($entitlement->getToDate())) {
                                $availableDays = 0;
                            }

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
                                if (!isset($change[$leaveId])) {
                                    $change[$leaveId] = array();
                                }
                                $change[$leaveId][$entitlement->id] = $leaveLength;                                
                            }
                            $getNextDate = true;
                        } else {
                            $entitlement->days_used = $entitlement->no_of_days;
                            $leaveLength -= $availableDays;

                            $leaveId = $leaveDate->getId();

                            if (empty($leaveId)) {                            
                                if (!isset($current[$leaveDate->getDate()])) {
                                    $current[$leaveDate->getDate()] = array();
                                }
                                $current[$leaveDate->getDate()][$entitlement->id] = $availableDays;                                
                            } else {
                                if (!isset($change[$leaveId])) {
                                    $change[$leaveId] = array();
                                }
                                $change[$leaveId][$entitlement->id] = $availableDays;                                 
                            }

                            $availableDays = 0;
                            $getNextDate = false;
                        }
                    } else {
                        $entitlement = array_shift($entitlements);           
                        if (is_null($entitlement)) {
                            
                            if (empty($leaveDates) && empty($leaveWithoutEntitlement) && $getNextDate) {
                                $entitlementsOk = true;
                            }
                        } else {
                            $availableDays = $entitlement->getAvailableDays();                        
                        }
                    }
                }
            }

        }
        

        if ($allowNoEntitlements || ($entitlementsOk && (count($change) > 0 || count($current) > 0))) {
            $result = array('current' => $current, 'change' => $change);
        }
        
        return $result;        
    }

    public function handleEntitlementStatusChange() {
        
    }

    public function handleLeaveCancel($leave) {
        
        $result = false;
        $current = array();
        $change = array();
        
        $entitlementArray = $this->getLeaveEntitlementService()->getEntitlementUsageForLeave($leave->id);        
        
        if (count($entitlementArray) > 0) {
            $minDate = null;
            $maxDate = null;
            
            // reduce entitlement usage for this leave

            foreach ($entitlementArray as $entitlementItem) {

                $entitlementItem['days_used'] -= $entitlementItem['length_days'];
                if ($entitlementItem['days_used'] < 0) {
                    $entitlementItem['days_used'] = 0;
                }
                if (is_null($minDate)) {
                    $minDate = $entitlementItem['from_date'];
                    $maxDate = $entitlementItem['to_date'];
                } else {
                    if (strtotime($minDate) > strtotime($entitlementItem['from_date'])) {
                        $minDate = strtotime($entitlementItem['from_date']);
                    }
                    if (strtotime($maxDate) < strtotime($entitlementItem['to_date'])) {
                        $maxDate = strtotime($entitlementItem['to_date']);
                    }
                    
                }
            }
            // Get leave without entitlements between from_date and to_date
            $leaveList = $this->getLeaveEntitlementService()
                    ->getLeaveWithoutEntitlements($leave->getEmpNumber(), $leave->getLeaveTypeId(), $minDate, $maxDate); 
            
            // remove current leave from list
            $leaveDates = array();
            foreach ($leaveList as $leaveDateTemp ) {
                if ($leaveDateTemp['id'] != $leave->getId()) {
                    $leaveDates[] = $leaveDateTemp;
                }
            }

            $entitlements = array();
            foreach($entitlementArray as $entitlementItem) {
                $entitlement = new LeaveEntitlement();
                $entitlement->setId($entitlementItem['id']);
                $entitlement->setNoOfDays($entitlementItem['no_of_days']);
                $newDaysUsed = $entitlementItem['days_used'] - $entitlementItem['length_days'];
                if ($newDaysUsed < 0) {
                    $newDaysUsed = 0;
                }
                
                $entitlement->setDaysUsed($newDaysUsed);
                $entitlement->setFromDate($entitlementItem['from_date']);
                $entitlement->setToDate($entitlementItem['to_date']);
                
                $entitlements[] = $entitlement;
            }

            reset($leaveDates);

            $getNextDate = true;
            $entitlementsOk = false;
            $leaveDate = null;
            $leaveLength = 0;

            $leaveWithoutEntitlement = array();

            $entitlement = array_shift($entitlements); 

            if (!is_null($entitlement)) {
                $availableDays = $entitlement->getAvailableDays();         
            }

            while (!is_null($entitlement)) {
                if ($availableDays > 0) {

                    if ($getNextDate) {
                        $leaveDate = array_shift($leaveDates);
                        if (is_null($leaveDate)) {

                            $entitlementsOk = empty($leaveWithoutEntitlement);
                            $leaveLength = 0;
                            break;
                        } else {

                            $leaveLength = $leaveDate['length_days'];
                            $getNextDate = false;

                        }
                    }
                    
                    if ($leaveLength <= 0) {

                        $getNextDate = true;                    
                    } else if (!$entitlement->withinPeriod($leaveDate['date'])) {

                        if (strtotime($leaveDate['date']) < strtotime($entitlement->getFromDate())) {
                            $getNextDate = true;
                            $leaveWithoutEntitlement[] = $leaveDate;
                        } else if (strtotime($leaveDate['date']) > strtotime($entitlement->getToDate())) {
                            $availableDays = 0;
                        }

                    } else if ($leaveLength <= $availableDays) {

                        $entitlement->days_used += $leaveLength;
                        $availableDays -= $leaveLength;

                        $leaveId = $leaveDate['id'];

                        if (empty($leaveId)) {
                            if (!isset($current[$leaveDate['date']])) {
                                $current[$leaveDate['date']] = array();
                            }
                            $current[$leaveDate['date']][$entitlement->id] = $leaveLength;

                        } else {

                            if (!isset($change[$leaveId])) {
                                $change[$leaveId] = array();
                            }
                            $change[$leaveId][$entitlement->id] = $leaveLength;                                
                        }
                        $getNextDate = true;
                    } else {

                        $entitlement->days_used = $entitlement->no_of_days;
                        $leaveLength -= $availableDays;

                        $leaveId = $leaveDate['id'];

                        if (empty($leaveId)) {                            
                            if (!isset($current[$leaveDate['date']])) {
                                $current[$leaveDate['date']] = array();
                            }
                            $current[$leaveDate['date']][$entitlement->id] = $availableDays;                                
                        } else {
                            if (!isset($change[$leaveId])) {
                                $change[$leaveId] = array();
                            }
                            $change[$leaveId][$entitlement->id] = $availableDays;                                 
                        }

                        $availableDays = 0;
                        $getNextDate = false;
                    }
                } else {
                    $entitlement = array_shift($entitlements);           
                    if (is_null($entitlement)) {

                        if (empty($leaveDates) && empty($leaveWithoutEntitlement) && $getNextDate) {
                            $entitlementsOk = true;
                        }
                    } else {
                        $availableDays = $entitlement->getAvailableDays();                        
                    }
                }
            }

        }
        

        $result = array('current' => $current, 'change' => $change);

        return $result;          
    }
    
    public function handleLeavePeriodChange($leavePeriodForToday, $oldMonth, $oldDay, $newMonth, $newDay) {
        
        try {
            
            $pdo = Doctrine_Manager::connection()->getDbh();

            $leavePeriodStartDate = new DateTime($leavePeriodForToday[0]);
            $leavePeriodEndDate = new DateTime($leavePeriodForToday[1]);
            
            // If current leave period start date is 1/1 and new date is 1/1, 
            if ($leavePeriodStartDate->format('n') == 1 && $leavePeriodStartDate->format('j') == 1 && $newMonth == 1 && $newDay == 1) {
                $newEndDateForCurrentPeriod = $leavePeriodStartDate->format('Y') . '-12-31';
            } else {
                $tmp = new DateTime();
                $tmp->setDate($leavePeriodStartDate->format('Y') + 1, $newMonth, $newDay);
                $tmp->sub(new DateInterval('P1D'));
                $newEndDateForCurrentPeriod = $tmp->format('Y-m-d');
            }
                        
         $queryCurrentPeriod = 'UPDATE ohrm_leave_entitlement e SET ' .
                    "e.to_date = '$newEndDateForCurrentPeriod' " .                    
                    "WHERE e.deleted = 0 AND e.from_date = :fromDate AND e.to_date = :toDate ";
                    
            $stmt = $pdo->prepare($queryCurrentPeriod);                        
            $stmt->execute(array(':fromDate'=> $leavePeriodForToday[0],':toDate'=>$leavePeriodForToday[1]));
                        
           $queryFuturePeriods = 'UPDATE ohrm_leave_entitlement e SET ' .
                    "e.from_date = CONCAT(YEAR(e.from_date), '-',:newMonth , '-', :newDay), " .
                    "e.to_date = DATE_SUB(CONCAT(YEAR(e.from_date) + 1, '-', :newMonth, '-', :newDay), INTERVAL 1 DAY) " .                    
                    "WHERE e.deleted = 0 AND MONTH(e.from_date) = :oldMonth AND DAY(e.from_date) = :oldDay AND e.from_date > :fromDate ";
           
            $stmt = $pdo->prepare($queryFuturePeriods);                        
            $stmt->execute(array(':newMonth'=>$newMonth,':newDay'=>$newDay,':oldMonth'=>$oldMonth,':oldDay'=>$oldDay,':fromDate'=>$leavePeriodForToday[1]));


        } catch (DaoException $e) {
            throw new DaoException($e->getMessage(), 0, $e);
        }
        
    }
}
