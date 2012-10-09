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
 * Description of viewMyLeaveEntitlements
 */
class viewMyLeaveEntitlementsAction extends viewLeaveEntitlementsAction {
    
    public function execute($request) {
        parent::execute($request);
        $this->setTemplate('viewLeaveEntitlements');
    }
    
    public function getForm() {
        $options = array('empNumber' => $this->getUser()->getAttribute('auth.empNumber'));
        return new MyLeaveEntitlementForm(array(), $options);
    }
    
    protected function showResultTableByDefault() {
        return true;
    }    
    
    protected function getTitle() {
        return 'My Leave Entitlements';
    }    
    
    protected function getDefaultFilters() {
        $filters = $this->form->getDefaults();
        
        $employee = array('empId' => $this->getUser()->getAttribute('auth.empNumber'));
        $filters['employee'] = $employee;
        
        return $filters;
    }    

}
