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
    
    public function testGetAvailableEntitlementsNone() { 
        
        $entitlements = array();
        
        $mockService = $this->getMock('LeaveEntitlementService', array('getValidLeaveEntitlements'));
        $mockService->expects($this->once())
                    ->method('getValidLeaveEntitlements')
                    //->with($ids)
                    ->will($this->returnValue($entitlements));
        $this->strategy->setLeaveEntitlementService($mockService);        
        
        $empNumber = 1;
        $leaveType = 2;
        
        $leave1 = new Leave();;
        $leave1->fromArray(array('id' => 1, 'date' => '2012-09-11', 'length_days' => 1));
        $leave2 = new Leave();;
        $leave2->fromArray(array('id' => 2, 'date' => '2012-09-12', 'length_days' => 1));        
                
        $leaveDates = array($leave1, $leave2);
        
        $results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        $this->assertTrue(!$results);

        
    }
    
    public function testGetAvailableEntitlementsOne1() {
        
        $entitlement1 = new LeaveEntitlement();
        $entitlement1->setId(1);
        $entitlement1->setEmpNumber(1);
        $entitlement1->setNoOfDays(3);
        $entitlement1->setLeaveTypeId(2);
        $entitlement1->setFromDate('2012-09-13');
        $entitlement1->setToDate('2012-11-28');
        $entitlement1->setCreditedDate('2012-05-01');
        $entitlement1->setNote('Created by Unit test');
        $entitlement1->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement1->setDeleted(0);        
        
        $entitlements = array(
            $entitlement1
        );
        
        $mockService = $this->getMock('LeaveEntitlementService', array('getValidLeaveEntitlements'));
        $mockService->expects($this->any())
                    ->method('getValidLeaveEntitlements')
                    //->with($ids)
                    ->will($this->returnValue($entitlements));
        $this->strategy->setLeaveEntitlementService($mockService);        
        
        $empNumber = 1;
        $leaveType = 2;
        
        $leave1 = new Leave();
        $leave1->fromArray(array('id' => 1, 'date' => '2012-09-13', 'length_days' => 1));
        $leave2 = new Leave();
        $leave2->fromArray(array('id' => 2, 'date' => '2012-09-14', 'length_days' => 1));        
        $leave3 = new Leave();
        $leave3->fromArray(array('id' => 3, 'date' => '2012-09-15', 'length_days' => 1));
        $leave4 = new Leave();
        $leave4->fromArray(array('id' => 2, 'date' => '2012-09-16', 'length_days' => 1)); 
        
        $leaveDates = array($leave1, $leave2, $leave3, $leave4);
        
        $results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        $this->assertTrue($results == false);
           
    }
    
    public function testGetAvailableEntitlementsOne2() {
        
        $entitlement1 = new LeaveEntitlement();
        $entitlement1->setId(1);
        $entitlement1->setEmpNumber(1);
        $entitlement1->setNoOfDays(3);
        $entitlement1->setLeaveTypeId(2);
        $entitlement1->setFromDate('2012-09-13');
        $entitlement1->setToDate('2012-11-28');
        $entitlement1->setCreditedDate('2012-05-01');
        $entitlement1->setNote('Created by Unit test');
        $entitlement1->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement1->setDeleted(0);        
        
        $entitlements = array(
            $entitlement1
        );
        
        $mockService = $this->getMock('LeaveEntitlementService', array('getValidLeaveEntitlements'));
        $mockService->expects($this->any())
                    ->method('getValidLeaveEntitlements')
                    //->with($ids)
                    ->will($this->returnValue(array_values($entitlements)));
        $this->strategy->setLeaveEntitlementService($mockService);        
        
        $empNumber = 1;
        $leaveType = 2;
        
        $leave1 = new Leave();
        $leave1->fromArray(array('id' => 1, 'date' => '2012-09-13', 'length_days' => 1));
        $leave2 = new Leave();
        $leave2->fromArray(array('id' => 2, 'date' => '2012-09-14', 'length_days' => 1));        
        $leave3 = new Leave();
        $leave3->fromArray(array('id' => 3, 'date' => '2012-09-15', 'length_days' => 1));
        $leave4 = new Leave();
        $leave4->fromArray(array('id' => 2, 'date' => '2012-09-16', 'length_days' => 1)); 
                
        $leaveDates2 = array($leave1, $leave2, $leave3);
        
        $results2 = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates2);
        $this->assertTrue($results2 !== false);        
        $this->verifyEntitlements($results2, $leaveDates2, array(1, 1, 1));           
    }
    
    public function testGetAvailableEntitlementsOne3() {
        
        $entitlement1 = new LeaveEntitlement();
        $entitlement1->setId(1);
        $entitlement1->setEmpNumber(1);
        $entitlement1->setNoOfDays(3);
        $entitlement1->setLeaveTypeId(2);
        $entitlement1->setFromDate('2012-09-13');
        $entitlement1->setToDate('2012-11-28');
        $entitlement1->setCreditedDate('2012-05-01');
        $entitlement1->setNote('Created by Unit test');
        $entitlement1->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement1->setDeleted(0);        
        
        $entitlements = array(
            $entitlement1
        );
        
        $mockService = $this->getMock('LeaveEntitlementService', array('getValidLeaveEntitlements'));
        $mockService->expects($this->any())
                    ->method('getValidLeaveEntitlements')
                    //->with($ids)
                    ->will($this->returnValue(array_values($entitlements)));
        $this->strategy->setLeaveEntitlementService($mockService);        
        
        $empNumber = 1;
        $leaveType = 2;
        
        $leave1 = new Leave();
        $leave1->fromArray(array('id' => 1, 'date' => '2012-09-13', 'length_days' => 1));
        $leave2 = new Leave();
        $leave2->fromArray(array('id' => 2, 'date' => '2012-09-14', 'length_days' => 1));        
        $leave3 = new Leave();
        $leave3->fromArray(array('id' => 3, 'date' => '2012-09-15', 'length_days' => 1));
        $leave4 = new Leave();
        $leave4->fromArray(array('id' => 2, 'date' => '2012-09-16', 'length_days' => 1)); 
                
        $leaveDates = array($leave1, $leave2);
        
        $results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        $this->assertTrue($results !== false);        
        $this->verifyEntitlements($results, $leaveDates, array(1, 1));           
    }
    
    public function testGetAvailableEntitlementsOne4() {
        
        $entitlement1 = new LeaveEntitlement();
        $entitlement1->setId(1);
        $entitlement1->setEmpNumber(1);
        $entitlement1->setNoOfDays(3);
        $entitlement1->setLeaveTypeId(2);
        $entitlement1->setFromDate('2012-09-13');
        $entitlement1->setToDate('2012-11-28');
        $entitlement1->setCreditedDate('2012-05-01');
        $entitlement1->setNote('Created by Unit test');
        $entitlement1->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement1->setDeleted(0);        
        
        $entitlements = array(
            $entitlement1
        );
        
        $mockService = $this->getMock('LeaveEntitlementService', array('getValidLeaveEntitlements'));
        $mockService->expects($this->any())
                    ->method('getValidLeaveEntitlements')
                    //->with($ids)
                    ->will($this->returnValue(array_values($entitlements)));
        $this->strategy->setLeaveEntitlementService($mockService);        
        
        $empNumber = 1;
        $leaveType = 2;
        
        $leave1 = new Leave();
        $leave1->fromArray(array('id' => 1, 'date' => '2012-09-13', 'length_days' => 1));
        $leave2 = new Leave();
        $leave2->fromArray(array('id' => 2, 'date' => '2012-09-14', 'length_days' => 1));        
        $leave3 = new Leave();
        $leave3->fromArray(array('id' => 3, 'date' => '2012-09-15', 'length_days' => 1));
        $leave4 = new Leave();
        $leave4->fromArray(array('id' => 2, 'date' => '2012-09-16', 'length_days' => 1));         
        
        $leaveDates = array($leave1, $leave2);
        
        $results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        $this->assertTrue($results !== false);        
        $this->verifyEntitlements($results, $leaveDates, array(1, 1));   
    }
    
    public function testGetAvailableEntitlementsOne5() {
        
        $entitlement1 = new LeaveEntitlement();
        $entitlement1->setId(1);
        $entitlement1->setEmpNumber(1);
        $entitlement1->setNoOfDays(3);
        $entitlement1->setLeaveTypeId(2);
        $entitlement1->setFromDate('2012-09-13');
        $entitlement1->setToDate('2012-11-28');
        $entitlement1->setCreditedDate('2012-05-01');
        $entitlement1->setNote('Created by Unit test');
        $entitlement1->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement1->setDeleted(0);        
        
        $entitlements = array(
            $entitlement1
        );
        
        $mockService = $this->getMock('LeaveEntitlementService', array('getValidLeaveEntitlements'));
        $mockService->expects($this->any())
                    ->method('getValidLeaveEntitlements')
                    //->with($ids)
                    ->will($this->returnValue(array_values($entitlements)));
        $this->strategy->setLeaveEntitlementService($mockService);        
        
        $empNumber = 1;
        $leaveType = 2;
        
        $leave1 = new Leave();
        $leave1->fromArray(array('id' => 1, 'date' => '2012-09-13', 'length_days' => 1));
        $leave2 = new Leave();
        $leave2->fromArray(array('id' => 2, 'date' => '2012-09-14', 'length_days' => 1));        
        $leave3 = new Leave();
        $leave3->fromArray(array('id' => 3, 'date' => '2012-09-15', 'length_days' => 1));
        $leave4 = new Leave();
        $leave4->fromArray(array('id' => 2, 'date' => '2012-09-16', 'length_days' => 1));          
        
        $leaveDates = array($leave1);
        
        $results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        $this->assertTrue($results !== false);        
        $this->verifyEntitlements($results, $leaveDates, array(1));    
    }    
    
    public function testGetAvailableEntitlementsOneEdges() {
        
        $entitlement1 = new LeaveEntitlement();
        $entitlement1->setId(1);
        $entitlement1->setEmpNumber(1);
        $entitlement1->setNoOfDays(3);
        $entitlement1->setLeaveTypeId(2);
        $entitlement1->setFromDate('2012-09-13');
        $entitlement1->setToDate('2012-11-28');
        $entitlement1->setCreditedDate('2012-05-01');
        $entitlement1->setNote('Created by Unit test');
        $entitlement1->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement1->setDeleted(0);        
        
        $entitlements = array(
            $entitlement1
        );
        
        $mockService = $this->getMock('LeaveEntitlementService', array('getValidLeaveEntitlements'));
        $mockService->expects($this->any())
                    ->method('getValidLeaveEntitlements')
                    //->with($ids)
                    ->will($this->returnValue($entitlements));
        $this->strategy->setLeaveEntitlementService($mockService);        
        
        $empNumber = 1;
        $leaveType = 2;
        
        $leave1 = new Leave();
        $leave1->fromArray(array('id' => 1, 'date' => '2012-09-12', 'length_days' => 1));
        $leave2 = new Leave();
        $leave2->fromArray(array('id' => 2, 'date' => '2012-09-13', 'length_days' => 1));        
        $leave3 = new Leave();
        $leave3->fromArray(array('id' => 3, 'date' => '2012-11-28', 'length_days' => 1));
        $leave4 = new Leave();
        $leave4->fromArray(array('id' => 2, 'date' => '2012-11-29', 'length_days' => 1)); 
        
        /*$leaveDates = array($leave1, $leave2);
        
        $results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        $this->assertTrue($results === false);*/
        
        $leaveDates = array($leave3, $leave4);
        
        $results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        $this->assertTrue($results === false);   
    }
    
    public function testGetAvailableEntitlementsTwo() {
        
        $entitlement1 = new LeaveEntitlement();
        $entitlement1->setId(1);
        $entitlement1->setEmpNumber(1);
        $entitlement1->setNoOfDays(3);
        $entitlement1->setLeaveTypeId(2);
        $entitlement1->setFromDate('2012-09-13');
        $entitlement1->setToDate('2012-09-14');
        $entitlement1->setCreditedDate('2012-05-01');
        $entitlement1->setNote('Created by Unit test');
        $entitlement1->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement1->setDeleted(0);     
        
        $entitlement2 = new LeaveEntitlement();
        $entitlement2->setId(2);
        $entitlement2->setEmpNumber(1);
        $entitlement2->setNoOfDays(2);
        $entitlement2->setLeaveTypeId(2);
        $entitlement2->setFromDate('2012-09-12');
        $entitlement2->setToDate('2012-09-15');
        $entitlement2->setCreditedDate('2012-05-01');
        $entitlement2->setNote('Created by Unit test');
        $entitlement2->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
        $entitlement2->setDeleted(0);    
        
        $entitlements = array(
            $entitlement1, $entitlement2
        );
        
        $mockService = $this->getMock('LeaveEntitlementService', array('getValidLeaveEntitlements'));
        $mockService->expects($this->any())
                    ->method('getValidLeaveEntitlements')
                    //->with($ids)
                    ->will($this->returnValue($entitlements));
        $this->strategy->setLeaveEntitlementService($mockService);        
        
        $empNumber = 1;
        $leaveType = 2;
        
        $leave1 = new Leave();
        $leave1->fromArray(array('id' => 1, 'date' => '2012-09-12', 'length_days' => 1));        
                
        $leaveDates = array($leave1);
        
        /*$results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        $this->assertTrue($results !== false);
        $this->verifyEntitlements($results, $leaveDates, array(2));
        */
        $leave2 = new Leave();
        $leave2->fromArray(array('id' => 2, 'date' => '2012-09-13', 'length_days' => 1));        
        $leave3 = new Leave();
        $leave3->fromArray(array('id' => 3, 'date' => '2012-09-14', 'length_days' => 1));
        $leave4 = new Leave();
        $leave4->fromArray(array('id' => 2, 'date' => '2012-09-15', 'length_days' => 1));         

        $leaveDates = array($leave1, $leave2);
        $results = $this->strategy->getAvailableEntitlements($empNumber, $leaveType, $leaveDates);
        $this->assertTrue($results !== false);
        $this->verifyEntitlements($results, $leaveDates, array(2, 2));        
    }    
    
    public function verifyEntitlements($results, $leaveDates, $entitlementIds) {
        
        $numDates = count($leaveDates);
        
        $this->assertEquals($numDates, count($results));
        $this->assertEquals($numDates, count($entitlementIds));
        
        for ($i = 0; $i < $numDates; $i++) {
            $result = $results[$i];
            $leaveDate = $leaveDates[$i];
            $entitlementId = $entitlementIds[$i];
            $this->assertEquals($leaveDate->getId(), $result->getId());
            $this->assertEquals($leaveDate->getLengthDays(), $result->getLengthDays());
            $this->assertEquals($leaveDate->getDate(), $result->getDate());
            $this->assertEquals($entitlementId, $result->getEntitlementId());
        }
        
    }

}

