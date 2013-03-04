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
 * Test class for home page dao
 */
class HomePageDaoTest extends PHPUnit_Framework_TestCase {
    
    private $homePageDao;
    private $fixture;
    private $testData;
    
    /**
     * Set up method
     */
    protected function setUp() {        

        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCorePlugin/test/fixtures/HomePageDao.yml';
        $this->testData = sfYaml::load($this->fixture);
        TestDataService::populate($this->fixture);                
        $this->homePageDao = new HomePageDao();        
    }
    
    public function testGetHomePagesInPriorityOrderOneRole() {
        $homePagesFixture = $this->testData['HomePage'];
        $expected = array($homePagesFixture[3], $homePagesFixture[2], $homePagesFixture[0]);
        $homePages = $this->homePageDao->getHomePagesInPriorityOrder(array(1));
        $this->compareHomePages($expected, $homePages);
        
        $expected = array($homePagesFixture[1]);
        $homePages = $this->homePageDao->getHomePagesInPriorityOrder(array(2));
        $this->compareHomePages($expected, $homePages);
        
        $expected = array($homePagesFixture[4]);
        $homePages = $this->homePageDao->getHomePagesInPriorityOrder(array(3));
        $this->compareHomePages($expected, $homePages);        
        
    }
    
    public function testGetHomePagesInPriorityOrderMultipleRole() {
        $homePagesFixture = $this->testData['HomePage'];
        $expected = array($homePagesFixture[3], $homePagesFixture[4], $homePagesFixture[2], $homePagesFixture[0], $homePagesFixture[1]);
        $homePages = $this->homePageDao->getHomePagesInPriorityOrder(array(1, 2, 3));
        $this->compareHomePages($expected, $homePages);
    }    
    
    /**
     * Test case for no matching home pages for user role
     */
    public function testGetHomePagesInPriorityOrderNoMatches() {
        $homePages = $this->homePageDao->getHomePagesInPriorityOrder(array(4));
        $this->assertEquals(0, count($homePages));
    }    
    
    /**
     * Test case for no matching home pages for user role
     */
    public function testGetHomePagesInPriorityNoUserRoles() {
        $homePages = $this->homePageDao->getHomePagesInPriorityOrder(array());
        $this->assertEquals(0, count($homePages));
    }     
    
    protected function compareHomePages($expected, $result) {
        $this->assertEquals(count($expected), count($result));
        
        for($i = 0; $i < count($expected); $i++) {
            $exp = $expected[$i];
            $res = $result[$i];
            
            $this->assertEquals($exp['id'], $res->getId());
            $this->assertEquals($exp['user_role_id'], $res->getUserRoleId());
            $this->assertEquals($exp['action'], $res->getAction());
            $this->assertEquals($exp['enable_class'], $res->getEnableClass());
            $this->assertEquals($exp['priority'], $res->getPriority());
        }
    }
}
