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
 * @group Leave 
 */
class LeaveEntitlementDaoTest extends PHPUnit_Framework_TestCase {

    /**
     * @var LeaveEntitlementDao 
     */
    private $dao;
    private $fixture;

    /**
     * Set up method
     */
    protected function setUp() {

        $this->dao = new LeaveEntitlementDao();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmLeavePlugin/test/fixtures/LeaveEntitlementDao.yml';
        TestDataService::populate($this->fixture);
    }
    
    /* Default search - should return all non-deleted records - sorted by fromdate */
    public function testSearchLeaveEntitlementsWithNoFilters() {
        $parameterHolder = new LeaveEntitlementSearchParameterHolder();
        $entitlementList = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        $expected = array($entitlementList[0], $entitlementList[2], $entitlementList[3], $entitlementList[1]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);                
    }      
    
    /* Test sorting */
    public function testSearchLeaveEntitlementsSorting() {
        $parameterHolder = new LeaveEntitlementSearchParameterHolder();
        $entitlementList = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        
        // sort by leave type name
        $parameterHolder->setOrderBy('Desc');
        $parameterHolder->setOrderField('leave_type');

        $expected = array($entitlementList[1], $entitlementList[2], $entitlementList[3], $entitlementList[0]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        $this->_compareEntitlements($expected, $results);  
        
        
        // sort by employee name
        $parameterHolder->setOrderBy('Asc');
        $parameterHolder->setOrderField('employee_name');

        $expected = array($entitlementList[1], $entitlementList[0], $entitlementList[2], $entitlementList[3]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        $this->_compareEntitlements($expected, $results);          
        
    }       
    
    public function testSearchLeaveEntitlementsWithAllFilters() {
        $parameterHolder = new LeaveEntitlementSearchParameterHolder();
        $entitlementList = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        $parameterHolder->setEmpNumber(2);
        $parameterHolder->setLeaveTypeId(6);
        $parameterHolder->setFromDate('2013-08-01');
        $parameterHolder->setToDate('2013-10-02');
        $expected = array($entitlementList[1]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);           
    }    

    public function testSearchLeaveEntitlementsByLeaveType() {
        $parameterHolder = new LeaveEntitlementSearchParameterHolder();
        $entitlementList = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        
        $parameterHolder->setLeaveTypeId(2);

        $expected = array($entitlementList[2], $entitlementList[3]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        $this->_compareEntitlements($expected, $results);          
        
        // Non existing leave type id
        $parameterHolder->setLeaveTypeId(21);

        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        $this->assertEquals(0, count($results));

        // Leave type with no entitlements
        $parameterHolder->setLeaveTypeId(7);

        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        $this->assertEquals(0, count($results));        
    }    
    
    public function testSearchLeaveEntitlementsByEmpNumber() {
        $parameterHolder = new LeaveEntitlementSearchParameterHolder();
        $entitlementList = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        
        // employee with multiple records
        $parameterHolder->setEmpNumber(1);
        $expected = array($entitlementList[0], $entitlementList[2], $entitlementList[3]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);           
        
        // employee with one record
        $parameterHolder->setEmpNumber(2);
        $expected = array($entitlementList[1]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);            
        
        // employee with no records
        $parameterHolder->setEmpNumber(4);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        $this->assertEquals(0, count($results));        
        
        // non existing employee
        $parameterHolder->setEmpNumber(100);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        $this->assertEquals(0, count($results));           
    }
    
    public function testSearchLeaveEntitlementsByDates() {
        $parameterHolder = new LeaveEntitlementSearchParameterHolder();
        $entitlementList = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        
        // date range with multiple records
        $parameterHolder->setFromDate('2012-03-01');
        $parameterHolder->setToDate('2012-07-01');
                
        $expected = array($entitlementList[0], $entitlementList[2], $entitlementList[3], $entitlementList[1]);
        $expected = array($entitlementList[0], $entitlementList[2], $entitlementList[3]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);         
        
        // from date matching entitlement from date
        $parameterHolder->setFromDate('2012-04-04');
        $parameterHolder->setToDate('2012-04-05');        
        $expected = array($entitlementList[0], $entitlementList[2]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);        
                
        $parameterHolder->setFromDate('2012-01-01');
        $parameterHolder->setToDate('2012-01-02');        
        $expected = array($entitlementList[0]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);   
        
        // to date matching entitlement to date
        $parameterHolder->setFromDate('2011-01-01');
        $parameterHolder->setToDate('2012-01-01');        
        $expected = array($entitlementList[0]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);        
        
        // from date matching entitlement to date
        $parameterHolder->setFromDate('2013-09-01');
        $parameterHolder->setToDate('2013-11-01');        
        $expected = array($entitlementList[1]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);            
    }
    
    public function testSearchLeaveEntitlementsDeletedFlag() {
        $parameterHolder = new LeaveEntitlementSearchParameterHolder();
        $entitlementList = TestDataService::loadObjectList('LeaveEntitlement', $this->fixture, 'LeaveEntitlement');
        
        // default - non-deleted
        $expected = array($entitlementList[0], $entitlementList[2], $entitlementList[3], $entitlementList[1]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);          
        
        // only deleted
        $parameterHolder->setDeletedFlag(true);
        $expected = array($entitlementList[4]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);           
        
        // both deleted and non-deleted
        $parameterHolder->setDeletedFlag(NULL);
        $expected = array($entitlementList[0], $entitlementList[2], $entitlementList[3], $entitlementList[4], $entitlementList[1]);
        $results = $this->dao->searchLeaveEntitlements($parameterHolder);
        
        $this->_compareEntitlements($expected, $results);         
    }
    
    public function testSaveLeaveEntitlement() {
        
    }
    
    public function testDeleteLeaveEntitlements() {
        
    }
    
    protected function _compareEntitlements($expected, $results) {
        $this->assertEquals(count($expected), count($results));
        
        for ($i = 0; $i < count($expected); $i++) {         
            $this->_compareEntitlement($expected[$i], $results[$i]);
        }
    }
    
    protected function _compareEntitlement($expected, $actual) {
        $this->assertEquals($expected->getId(), $actual->getId());
        $this->assertEquals($expected->getEmpNumber(), $actual->getEmpNumber());
        $this->assertEquals($expected->getNoOfDays(), $actual->getNoOfDays());
        $this->assertEquals($expected->getLeaveTypeId(), $actual->getLeaveTypeId());
        $this->assertEquals($expected->getFromDate(), $actual->getFromDate());
        $this->assertEquals($expected->getToDate(), $actual->getToDate());
        $this->assertEquals($expected->getCreditedDate(), $actual->getCreditedDate());
        $this->assertEquals($expected->getNote(), $actual->getNote());
        $this->assertEquals($expected->getEntitlementType(), $actual->getEntitlementType());
        $this->assertEquals($expected->getDeleted(), $actual->getDeleted());
        
    }
    
}

    