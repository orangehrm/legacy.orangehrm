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
 * Description of LeaveListBalanceCell
 */
class LeaveListBalanceCell extends Cell {
    
    public function __toString() {
        
        $request = $this->dataObject;        
        $leaveEntitlementService = new LeaveEntitlementService();
        $leavePeriodService = $leaveEntitlementService->getLeavePeriodService();
        
        $empNumber = $request->getEmpNumber();
        $leaveTypeId = $request->getLeaveTypeId();        

        $dates = $request->getLeaveDates();

        $leaveBalance = NULL;

        if (count($dates) == 1) {
            $startDate = $dates[0];
            $leaveBalance = $leaveEntitlementService->getLeaveBalance($empNumber, $leaveTypeId, $startDate);
        } else {
            $startDate = $dates[0];
            $endDate = $dates[count($dates) - 1];
            
            $leavePeriodForStartDate = $leavePeriodService->getCurrentLeavePeriodByDate($startDate);
            $leavePeriodForEndDate = $leavePeriodService->getCurrentLeavePeriodByDate($endDate);
            
            // check if start date and end date are in the same leave period            
            if (($leavePeriodForStartDate[0] == $leavePeriodForEndDate[0]) && 
                    ($leavePeriodForStartDate[1] == $leavePeriodForEndDate[1])) {
                $leaveBalance = $leaveEntitlementService->getLeaveBalance($empNumber, $leaveTypeId, $startDate);
            } else {
                $startPeriodBalance = $leaveEntitlementService->getLeaveBalance($empNumber, $leaveTypeId, $startDate);
                $endPeriodBalance = $leaveEntitlementService->getLeaveBalance($empNumber, $leaveTypeId, $endDate);
                
                $leaveBalance = array(
                    array('start' => set_datepicker_date_format($leavePeriodForStartDate[0]), 
                          'end' => set_datepicker_date_format($leavePeriodForStartDate[1]), 
                          'balance' => $startPeriodBalance->getBalance()),
                    array('start' => set_datepicker_date_format($leavePeriodForEndDate[0]), 
                          'end' => set_datepicker_date_format($leavePeriodForEndDate[1]), 
                          'balance' => $endPeriodBalance->getBalance())
                );
            }            
        }
        
        if ($leaveBalance instanceof LeaveBalance) {
            return number_format($leaveBalance->getBalance(), 2);        
        } else if (is_array($leaveBalance)) {


            $html = "<a href='#' onclick='viewLeaveBalance(" . json_encode($leaveBalance) . ")' ?>" . __('View') . "</a>";
            return $html;
        }
    }

}
