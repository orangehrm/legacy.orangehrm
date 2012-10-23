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
 * Description of FIFOEntitlementConsumptionStrategyTest
 */
class FIFOEntitlementConsumptionStrategyTest extends PHPUnit_Framework_TestCase {

    private $strategy;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->strategy = new FIFOEntitlementConsumptionStrategy();
    }
    
    public function testGetAvailableEntitlements() {
        
        $entitlement1 = new LeaveEntitlement();
        $entitlement1->setId(1);
        $entitlement1->setEmpNumber(1);
        $entitlement1->setNoOfDays(12);
        $entitlement1->setLeaveTypeId(2);
        $entitlement1->setFromDate('2012-09-13');
        $entitlement1->setToDate('2012-11-28');
        $entitlement1->setCreditedDate('2012-05-01');
        $entitlement1->setNote('Created by Unit test');
        $entitlement1->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement1->setDeleted(0);     
        
        $entitlement2 = new LeaveEntitlement();
        $entitlement2->setId(2);
        $entitlement2->setEmpNumber(1);
        $entitlement2->setNoOfDays(12);
        $entitlement2->setLeaveTypeId(2);
        $entitlement2->setFromDate('2012-09-15');
        $entitlement2->setToDate('2012-11-28');
        $entitlement2->setCreditedDate('2012-05-01');
        $entitlement2->setNote('Created by Unit test');
        $entitlement2->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement2->setDeleted(0);    
        
        $entitlements = array(
            $entitlement1, $entitlement2
        );
        
        $empNumber = 1;
        $leaveType = 2;
        $leaveDates = array('2012-09-15' => 1, '2012-09-16' => 1);
                
        $mockService = $this->getMock('LeaveEntitlementService', array('getValidLeaveEntitlements'));
        $mockService->expects($this->once())
                    ->method('getValidLeaveEntitlements')
                    //->with($ids)
                    ->will($this->returnValue($entitlements));
        $this->strategy->setLeaveEntitlementService($mockService);
        $results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        
        $expected = array(1 => 2);
        
        $this->assertEquals($expected, $results);
        
    }
    

}

