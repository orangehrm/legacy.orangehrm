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
 * LeaveEntitlementServiceTest
 * 
 * @group Leave 
 */
class LeaveEntitlementServiceTest extends PHPUnit_Framework_TestCase {

    private $service;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->service = new LeaveEntitlementService();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmLeavePlugin/test/fixtures/LeaveEntitlement.yml';        
    }
    
    public function testSearchLeaveEntitlements() {
        $leaveEntitlements  = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        $parameterHolder = new LeaveEntitlementSearchParameterHolder();

        $mockDao = $this->getMock('LeaveEntitlementDao', array('searchLeaveEntitlements'));
        $mockDao->expects($this->once())
                    ->method('searchLeaveEntitlements')
                    ->with($parameterHolder)
                    ->will($this->returnValue($leaveEntitlements));

        $this->service->setLeaveEntitlementDao($mockDao);
        $results = $this->service->searchLeaveEntitlements($parameterHolder);      
        
        $this->assertEquals($leaveEntitlements, $results);
    }
    
    public function testSaveLeaveEntitlement() {
        $leaveEntitlements  = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        $leaveEntitlement = $leaveEntitlements[0];

        $mockDao = $this->getMock('LeaveEntitlementDao', array('saveLeaveEntitlement'));
        $mockDao->expects($this->once())
                    ->method('saveLeaveEntitlement')
                    ->with($leaveEntitlement)
                    ->will($this->returnValue($leaveEntitlement));

        $this->service->setLeaveEntitlementDao($mockDao);
        $result = $this->service->saveLeaveEntitlement($leaveEntitlement);      
        
        $this->assertEquals($leaveEntitlement, $result);        
    }
    
    public function testDeleteLeaveEntitlements() {
        $ids = array(2, 33, 12);

        $mockDao = $this->getMock('LeaveEntitlementDao', array('deleteLeaveEntitlements'));
        $mockDao->expects($this->once())
                    ->method('deleteLeaveEntitlements')
                    ->with($ids)
                    ->will($this->returnValue(count($ids)));

        $this->service->setLeaveEntitlementDao($mockDao);
        $result = $this->service->deleteLeaveEntitlements($ids);      
        
        $this->assertEquals(count($ids), $result);            
    }
    
    public function testGetLeaveEntitlement() {
        $id = 2;
        $leaveEntitlements = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        $leaveEntitlement = $leaveEntitlements[0];

        $mockDao = $this->getMock('LeaveEntitlementDao', array('getLeaveEntitlement'));
        $mockDao->expects($this->once())
                ->method('getLeaveEntitlement')
                ->with($id)
                ->will($this->returnValue($leaveEntitlement));

        $this->service->setLeaveEntitlementDao($mockDao);
        $result = $this->service->getLeaveEntitlement($id);

        $this->assertEquals($leaveEntitlement, $result);
    }

    
}
