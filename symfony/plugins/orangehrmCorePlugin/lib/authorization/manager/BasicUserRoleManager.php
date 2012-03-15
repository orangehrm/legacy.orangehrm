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
 * Description of BasicUserRoleManager
 *
 */
class BasicUserRoleManager extends AbstractUserRoleManager {
    
    protected $employeeService;
    protected $systemUserService;
    protected $screenPermissionService;
    
    public function getScreenPermissionService() {
        if (empty($this->screenPermissionService)) {
            $this->screenPermissionService = new ScreenPermissionService();
        }         
        return $this->screenPermissionService;
    }

    public function setScreenPermissionService($screenPermissionService) {
        $this->screenPermissionService = $screenPermissionService;
    }

    public function getSystemUserService() {
        if (empty($this->systemUserService)) {
            $this->systemUserService = new SystemUserService();
        }        
        return $this->systemUserService;
    }

    public function setSystemUserService($systemUserService) {
        $this->systemUserService = $systemUserService;
    }

        public function getEmployeeService() {
        
        if (empty($this->employeeService)) {
            $this->employeeService = new EmployeeService();
        }
        return $this->employeeService;
    }

    public function setEmployeeService($employeeService) {
        $this->employeeService = $employeeService;
    }

        
    public function getAccessibleEntities($entityType, $operation = null, $returnType = null) {
    }
    
    
    /**
     * TODO: 'locations', 'system users', 'operational countries', 
     *       'user role' (only ess for regional admin),
     * 
     * @param type $entityType
     * @param type $operation
     * @param type $returnType
     * @return type 
     */
    public function getAccessibleEntityIds($entityType, $operation = null, $returnType = null) {
    
        $allIds = array();
        
        foreach ($this->userRoles as $role) {  
            $ids = array();

            switch ($entityType) {
                case 'Employee':
                    $ids = $this->getAccessibleEmployeeIds($role, $operation, $returnType);
                    break;

            }
            
            if (count($ids) > 0) {
                $allIds = array_unique(array_merge($allIds, $ids));
            }
        }
        
        return $allIds;
    }
    
    
    public function isEntityAccessible($entityType, $entityId, $operation = null, 
                                                $preferredUserRoleOrder = null) {
        
    }
    
    public function getAccessibleModules() {
        
    }
    
    public function isModuleAccessible($module) {
        
    }
    
    public function isScreenAccessible($module, $screen, $field) {
        
    }
    
    public function isFieldAccessible($module, $screen, $field) {
        
    }
    
    public function getScreenPermissions($module, $action) {
        $permissions = $this->getScreenPermissionService()->getScreenPermissions($module, $action, $this->userRoles);
        
        return $permissions;
    }
    
    protected function getUserRoles(SystemUser $user) {
        
        $user = $this->getSystemUserService()->getSystemUser($user->id);

        $roles = array($user->getUserRole());
        
        // Check for supervisor:
        $empNumber = $user->getEmpNumber();
        if (!empty($empNumber)) {
            if ($this->getEmployeeService()->isSupervisor($empNumber)) {
                $supervisorRole = $this->getSystemUserService()->getUserRole('Supervisor');
                if (!empty($supervisorRole)) {
                    $roles[] = $supervisorRole;
                }
            }
        }
        
        
        return $roles;
    }    
    
    protected function getAccessibleEmployeeIds($role, $operation = null, $returnType = null) {
        
        $employees = array();
        
        if ('Admin' == $role->getName()) {
            $employees = $this->getEmployeeService()->getEmployeeList('empNumber', 'ASC', true);
        } else if ('Supervisor' == $role->getName()) {
            $empNumber = $this->getUser()->getEmpNumber();
            if (!empty($empNumber)) {
                $employees = $this->getEmployeeService()->getSupervisorEmployeeChain($empNumber, true);
            }
        }
        
        $ids = array();
        
        foreach ($employees as $employee) {
            $ids[] = $employee->getEmpNumber();
        }

        return $ids;
        
    }
}

