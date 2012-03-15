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
    
    /** @property BasicUserRoleManager $service */
    private $manager;
    
    /**
     * Set up method
     */
    protected function setUp() {        
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCorePlugin/test/fixtures/BasicUserRoleManager.yml';
        
        TestDataService::populate($this->fixture);
                
        $this->manager = new BasicUserRoleManager();
    }
    
    public function testGetAccessibleEmployeeIdsAdmin() {
        $users = TestDataService::loadObjectList('SystemUser', $this->fixture, 'SystemUser');   
        $allEmployees = TestDataService::loadObjectList('Employee', $this->fixture, 'Employee');
        
        // Default Admin user  (no employee)
        $defaultAdmin = $users[5];
        $this->manager->setUser($defaultAdmin);
        $result = $this->manager->getAccessibleEntityIds('Employee');
        $expected = $this->getEmployeeIds($allEmployees);
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);        
        
        // Admin user 
        $admin = $users[0];
        $this->manager->setUser($admin);
        $result = $this->manager->getAccessibleEntityIds('Employee');
        $expected = $this->getEmployeeIds($allEmployees);
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);
        
        // Admin + supervisor
        $adminSupervisor = $users[3];
        $this->manager->setUser($adminSupervisor);
        $result = $this->manager->getAccessibleEntityIds('Employee');
        $expected = $this->getEmployeeIds($allEmployees);
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);      
    }

    public function testGetAccessibleEmployeesSupervisor() {
        $users = TestDataService::loadObjectList('SystemUser', $this->fixture, 'SystemUser');   
        $allEmployees = TestDataService::loadObjectList('Employee', $this->fixture, 'Employee');
        
        // Supervisor with one subordinate
        $supervisor = $users[1];
        $this->manager->setUser($supervisor);
        $expected = array($allEmployees[2]->getEmpNumber());
        
        $result = $this->manager->getAccessibleEntityIds('Employee');
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);
        
        
        // Supervisor with multiple subordinates
        $supervisor = $users[6];
        $this->manager->setUser($supervisor);
        $expected = array($allEmployees[0]->getEmpNumber(), $allEmployees[2]->getEmpNumber(), 
                          $allEmployees[3]->getEmpNumber(), $allEmployees[4]->getEmpNumber());
        
        $result = $this->manager->getAccessibleEntityIds('Employee');
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);         
    }
    
    public function testGetAccessibleEmployeesESS() {
        $users = TestDataService::loadObjectList('SystemUser', $this->fixture, 'SystemUser');   
        
        // Supervisor with one subordinate
        $essUser = $users[4];
        $this->manager->setUser($essUser);
        
        $result = $this->manager->getAccessibleEntityIds('Employee');
        $this->assertEquals(0, count($result));        
    }    
    
    public function testGetAccessibleSystemUsersAdmin() {
        $users = TestDataService::loadObjectList('SystemUser', $this->fixture, 'SystemUser');   
        $expected = $this->getObjectIds($users);

        // Default Admin user  (no employee)
        $defaultAdmin = $users[5];
        $this->manager->setUser($defaultAdmin);
        $result = $this->manager->getAccessibleEntityIds('SystemUser');
        
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);        
        
        // Admin user 
        $admin = $users[0];
        $this->manager->setUser($admin);
        $result = $this->manager->getAccessibleEntityIds('SystemUser');
        
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);   
        
        // Admin + supervisor
        $adminSupervisor = $users[3];
        $this->manager->setUser($adminSupervisor);
        $result = $this->manager->getAccessibleEntityIds('SystemUser');
        
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);       
    }    
    
    public function testGetAccessibleSystemUsersESSSupervisor() {
        $users = TestDataService::loadObjectList('SystemUser', $this->fixture, 'SystemUser');   
        
        // Supervisor with one subordinate
        $supervisor = $users[1];
        $this->manager->setUser($supervisor);
        
        $result = $this->manager->getAccessibleEntityIds('SystemUser');        
        $this->assertEquals(0, count($result));  
                
        // Supervisor with multiple subordinates
        $supervisor = $users[6];
        $this->manager->setUser($supervisor);
        
        $result = $this->manager->getAccessibleEntityIds('SystemUser');        
        $this->assertEquals(0, count($result));    
        
        // ESS user
        $essUser = $users[4];
        $this->manager->setUser($essUser);
        
        $result = $this->manager->getAccessibleEntityIds('SystemUser');        
        $this->assertEquals(0, count($result));           
    }  
    
    public function testGetAccessibleOperationalCountriesAdmin() {
        $users = TestDataService::loadObjectList('SystemUser', $this->fixture, 'SystemUser'); 
        $operationalCountries = TestDataService::loadObjectList('OperationalCountry', $this->fixture, 'OperationalCountry'); 
        
        
        $expected = $this->getObjectIds($operationalCountries);

        // Default Admin user  (no employee)
        $defaultAdmin = $users[5];
        $this->manager->setUser($defaultAdmin);
        $result = $this->manager->getAccessibleEntityIds('OperationalCountry');
        
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);        
        
        // Admin user 
        $admin = $users[0];
        $this->manager->setUser($admin);
        $result = $this->manager->getAccessibleEntityIds('OperationalCountry');
        
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);   
        
        // Admin + supervisor
        $adminSupervisor = $users[3];
        $this->manager->setUser($adminSupervisor);
        $result = $this->manager->getAccessibleEntityIds('OperationalCountry');
        
        $this->assertEquals(count($expected), count($result));
        $this->compareArrays($expected, $result);       
    }    
    
    public function xtestGetAccessibleOperationalCountriesESSSupervisor() {
        $users = TestDataService::loadObjectList('SystemUser', $this->fixture, 'SystemUser');   
        
        // Supervisor with one subordinate
        $supervisor = $users[1];
        $this->manager->setUser($supervisor);
        
        $result = $this->manager->getAccessibleEntityIds('SystemUser');        
        $this->assertEquals(0, count($result));  
                
        // Supervisor with multiple subordinates
        $supervisor = $users[6];
        $this->manager->setUser($supervisor);
        
        $result = $this->manager->getAccessibleEntityIds('SystemUser');        
        $this->assertEquals(0, count($result));    
        
        // ESS user
        $essUser = $users[4];
        $this->manager->setUser($essUser);
        
        $result = $this->manager->getAccessibleEntityIds('SystemUser');        
        $this->assertEquals(0, count($result));           
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
    
    public function testGetScreenPermissions() {
        
        $userRole = new UserRole();
        $userRole->setId(1);
        $userRole->setName('Admin');
        
        $user = new SystemUser();
        $user->setId(1);
        $user->setEmpNumber(NULL);
        $user->setUserRole($userRole);
        
        $systemUserService = $this->getMock('SystemUserService', array('getSystemUser'));
        
        $systemUserService->expects($this->once())
                ->method('getSystemUser')
                ->with($user->getId())
                ->will($this->returnValue($user));
        
        $this->manager->setSystemUserService($systemUserService);
        $this->manager->setUser($user);       
        
        $mockScreenPermissionService = $this->getMock('ScreenPermissionService', array('getScreenPermissions'));
        $permission = new ResourcePermission(true, false, true, false);
        
        $module = 'admin';
        $action = 'testAction';
        $roles = array($userRole);
        
        $mockScreenPermissionService->expects($this->once())
                ->method('getScreenPermissions')
                ->with($module, $action, $roles)
                ->will($this->returnValue($permission));
        
        $this->manager->setScreenPermissionService($mockScreenPermissionService);   
        
        $result = $this->manager->getScreenPermissions($module, $action);        
        
        $this->assertEquals($permission, $result);
        
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
    
    protected function compareEmployees($expected, $actual) {
        $this->assertEquals(count($expected), count($actual));
        
        foreach($expected as $expectedEmployee) {
            $found = false;
            
            foreach($actual as $employee) {
                
                if ($employee->getEmpNumber() == $expectedEmployee->getEmpNumber()) {
                    $found = true;
                    break;
                }
            }
            
            $this->assertTrue($found, 'Expected Employee (id = ' . $expectedEmployee->getEmpNumber() . ' not found');
        }        
    }
    
    protected function compareArrays($expected, $actual) {
        $this->assertEquals(count($expected), count($actual));
        
        $diff = array_diff($expected, $actual);
        $this->assertEquals(0, count($diff), $diff);       
    }    
    
    protected function getEmployeeIds($employees) {
        $ids = array();
        
        foreach ($employees as $employee) {
            $ids[] = $employee->getEmpNumber();
        }
        
        return $ids;
    }
    
    protected function getObjectIds($users) {
        $ids = array();
        
        foreach ($users as $user) {
            $ids[] = $user->getId();
        }
        
        return $ids;
    }    
}

/* Extend class to get access to protected method */
class TestBasicUserRoleManager extends BasicUserRoleManager {
    public function getUserRolesPublic($user) {
        return $this->getUserRoles($user);
    }
}


