<?php
/*
 *
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
 * Leave period service test
 * @group Leave 
 */
class LeavePeriodHistoryServiceTest extends PHPUnit_Framework_TestCase {

    private $leavePeriodService;
    private $fixture;

    protected function setUp() {

        $this->leavePeriodService = new LeavePeriodService();
        $leaveEntitlementService = new LeaveEntitlementService();
        $leaveEntitlementService->setLeaveEntitlementStrategy(new FIFOEntitlementConsumptionStrategy());
        $this->leavePeriodService->setLeaveEntitlementService($leaveEntitlementService);
        
        TestDataService::truncateTables(array('LeavePeriodHistory'));
    }
    /**
     * @expectedException ServiceException
     */
    public function testGetGeneratedLeavePeriodListDateIsNotSet(){

        $result = $this->leavePeriodService->getGeneratedLeavePeriodList();
       
    }
    
    public function testGetGeneratedLeavePeriodListDefineAs2012Jan1st(){
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(1);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2010-01-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        
        $result = $this->leavePeriodService->getGeneratedLeavePeriodList();
        $this->assertEquals(array(array('2010-01-01','2010-12-31'),array('2011-01-01','2011-12-31'),array('2012-01-01','2012-12-31'),array('2013-01-01','2013-12-31')),$result);
        
        
    }
    
     public function testGetGeneratedLeavePeriodListDefineAs2010Jan1st(){
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(1);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2010-01-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        
        $result = $this->leavePeriodService->getGeneratedLeavePeriodList();
        $this->assertEquals(array(array('2010-01-01','2010-12-31'),array('2011-01-01','2011-12-31'),array('2012-01-01','2012-12-31'),array('2013-01-01','2013-12-31')),$result);
        
        
    }

     public function testGetGeneratedLeavePeriodListForLeapYear(){
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(3);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2010-01-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        
        $result = $this->leavePeriodService->getGeneratedLeavePeriodList();
        $this->assertEquals(array(array('2009-03-01','2010-02-28'),array('2010-03-01','2011-02-28'),array('2011-03-01','2012-02-29'),array('2012-03-01','2013-02-28'),array('2013-03-01','2014-02-28')),$result);
        
        
    }
    
    public function testGetGeneratedLeavePeriodListDefineAs2010Jan1stAnd2012Jan1st(){
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(1);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2010-10-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(1);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2011-08-04');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(1);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2012-08-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        
        $result = $this->leavePeriodService->getGeneratedLeavePeriodList();
        $this->assertEquals(array(array('2010-01-01','2010-12-31'),array('2011-01-01','2011-12-31'),array('2012-01-01','2012-12-31'),array('2013-01-01','2013-12-31')),$result);
        
        
    }
    
     public function testGetGeneratedLeavePeriodListCase1(){
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(1);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2010-10-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(2);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2011-08-04');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(3);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2012-08-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        // work around for cached generated leave period list
        $newLeavePeriodService = new LeavePeriodService();
        $newLeavePeriodService->setLeaveEntitlementService($this->leavePeriodService->getLeaveEntitlementService());                
        $result= $newLeavePeriodService->getGeneratedLeavePeriodList();
        
        $this->assertEquals(array(array('2010-01-01','2010-12-31'),array('2011-01-01','2012-01-31'),array('2012-02-01','2013-02-28'),array('2013-03-01','2014-02-28')),$result);
        
        
    }
    
      public function testGetGeneratedLeavePeriodListCase2(){
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(2);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2010-01-01');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(1);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2010-01-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(1);
        $leavePeriodHistory->setLeavePeriodStartDay(2);
        $leavePeriodHistory->setCreatedAt('2010-01-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        // work around for cached generated leave period list
        $newLeavePeriodService = new LeavePeriodService();
        $newLeavePeriodService->setLeaveEntitlementService($this->leavePeriodService->getLeaveEntitlementService());                
        $result= $newLeavePeriodService->getGeneratedLeavePeriodList();

        $this->assertEquals(array(array('2009-02-01','2011-01-01'),array('2011-01-02','2012-01-01'),array('2012-01-02','2013-01-01'),array('2013-01-02','2014-01-01')),$result);
        
        
    }
    
    public function testGetCurrentLeavePeriodByDate( ){
        $leavePeriodHistory = new LeavePeriodHistory();
        $leavePeriodHistory->setLeavePeriodStartMonth(1);
        $leavePeriodHistory->setLeavePeriodStartDay(1);
        $leavePeriodHistory->setCreatedAt('2010-01-02');
        
        $this->leavePeriodService->saveLeavePeriodHistory( $leavePeriodHistory );
        
        
        $result = $this->leavePeriodService->getCurrentLeavePeriodByDate('2012-01-01');
       
        $this->assertEquals(array('2012-01-01','2012-12-31'),$result);
        
         $result = $this->leavePeriodService->getCurrentLeavePeriodByDate('2013-01-04');
       
        $this->assertEquals(array('2013-01-01','2013-12-31'),$result);
     
    }
    
    
}
