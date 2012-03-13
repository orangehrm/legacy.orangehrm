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
 */

/**
 * Description of AbstractUserRoleManagerTest
 *
 */
class BasicUserRoleManagerTest extends PHPUnit_Framework_TestCase {
    
    /** @property UserRoleManagerService $service */
    private $manager;
    
    /**
     * Set up method
     */
    protected function setUp() {        
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCorePlugin/test/fixtures/BasicUserRoleManager.yml';
        
        TestDataService::populate($this->fixture);
                
        $this->manager = new BasicUserRoleManager();
    }

    public function testGetUserRoles() {
        $this->manager = new TestBasicUserRoleManager();
        $users = TestDataService::loadObjectList('SystemUser', $this->fixture, 'SystemUser');
        
        // 0 - Admin (also ESS?)
        $roles = $this->manager->getUserRolesPublic($users[0]);
        $this->compareUserRoles(array('Admin'), $roles);
        
        // 1 - ESS, Supervisor   
        $roles = $this->manager->getUserRolesPublic($users[1]);
        $this->compareUserRoles(array('ESS', 'Supervisor'), $roles);
        
        // 2 - ESS
        $roles = $this->manager->getUserRolesPublic($users[2]);
        $this->compareUserRoles(array('ESS'), $roles);
        
        // 3 - Admin, Supervisor
        $roles = $this->manager->getUserRolesPublic($users[3]);
        $this->compareUserRoles(array('Admin', 'Supervisor'), $roles);
        
        // 4 - ESS
        $roles = $this->manager->getUserRolesPublic($users[4]);
        $this->compareUserRoles(array('ESS'), $roles);
        
        // 5 - Admin (Default admin)
        $roles = $this->manager->getUserRolesPublic($users[5]);
        $this->compareUserRoles(array('Admin'), $roles);
       
    }
    
    protected function compareUserRoles($expected, $actual) {
        $this->assertEquals(count($expected), count($actual));
        foreach($expected as $role) {
            $found = false;
            
            foreach($actual as $roleObject) {
                
                if ($roleObject->name == $role) {
                    $found = true;
                    break;
                }
            }
            
            $this->assertTrue($found, 'Expected Role ' . $role . ' not found');
        }
    }
}

/* Extend class to get access to protected method */
class TestBasicUserRoleManager extends BasicUserRoleManager {
    public function getUserRolesPublic($user) {
        return $this->getUserRoles($user);
    }
}


