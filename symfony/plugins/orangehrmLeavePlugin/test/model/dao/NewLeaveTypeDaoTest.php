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
 * Description of TestNewLeaveTypeDao
 */
class NewLeaveTypeDaoTest extends PHPUnit_Framework_TestCase {

    /**
     * @var NewLeaveTypeDao 
     */
    private $dao;
    private $fixture;

    /**
     * Set up method
     */
    protected function setUp() {

        $this->dao = new NewLeaveTypeDao();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmLeavePlugin/test/fixtures/LeaveType.yml';
        TestDataService::populate($this->fixture);
    }
    
    public function testGetLeaveTypeList() {
             
        $entitlementList = TestDataService::loadObjectList('LeaveType', $this->fixture, 'LeaveType');
        $expected = array($entitlementList[3], $entitlementList[0], $entitlementList[6], 
                          $entitlementList[1], $entitlementList[5]);
        $results = $this->dao->getLeaveTypeList();
        
        $this->_compareLeaveTypes($expected, $results);                
    }    
    
    public function testGetLeaveTypeListByCountryId() {
             
        $entitlementList = TestDataService::loadObjectList('LeaveType', $this->fixture, 'LeaveType');
        $expected = array($entitlementList[3], $entitlementList[1]);
        $results = $this->dao->getLeaveTypeList(1);
        
        $this->_compareLeaveTypes($expected, $results); 
        
        // country id without leave types
        $results = $this->dao->getLeaveTypeList(2);
        $this->assertEquals(0, count($results));
        
        // non-existing country id
        $results = $this->dao->getLeaveTypeList(12);
        $this->assertEquals(0, count($results));        
        
    }        
    
    protected function _compareLeaveTypes($expected, $results) {
        $this->assertEquals(count($expected), count($results));
        
        for ($i = 0; $i < count($expected); $i++) {                     
            $this->_compareLeaveType($expected[$i], $results[$i]);
        }
    }
    
    protected function _compareLeaveType($expected, $actual) {
        $this->assertEquals($expected->getId(), $actual->getId());
        $this->assertEquals($expected->getName(), $actual->getName());
        $this->assertEquals($expected->getDeleted(), $actual->getDeleted());
        $this->assertEquals($expected->getOperationalCountryId(), $actual->getOperationalCountryId());        
    }    
}

    
