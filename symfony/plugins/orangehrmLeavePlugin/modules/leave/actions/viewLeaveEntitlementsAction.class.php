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
 * View leave entitlement list
 *
 */
class viewLeaveEntitlementsAction extends sfAction {
    
    protected $leaveEntitlementService;
    
    public function getLeaveEntitlementService() {
        if (empty($this->leaveEntitlementService)) {
            $this->leaveEntitlementService = new LeaveEntitlementService();
        }
        return $this->leaveEntitlementService;
    }

    public function setLeaveEntitlementService($leaveEntitlementService) {
        $this->leaveEntitlementService = $leaveEntitlementService;
    }

    
    
    public function execute($request) {
        $this->form = new LeaveEntitlementForm();

        $this->showResultTable = false;
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $this->showResultTable = true;
                
                $searchParameters = $this->getSearchParameterObject($this->form);
                $results = $this->getLeaveEntitlementService()->searchLeaveEntitlements($searchParameters);
                $this->setListComponent($results, 0, 0);
            }
        }
    }
    
    protected function getSearchParameterObject($form) {
        $searchParameters = new LeaveEntitlementSearchParameterHolder();
        $employeeName = $form->getValue('employee_name');
        $id = $employeeName['empId'];
        $searchParameters->setEmpNumber($id);
        
        $searchParameters->setLeaveTypeId($form->getValue('leave_type_id'));
        $searchParameters->setFromDate($form->getValue('date_from'));
        $searchParameters->setToDate($form->getValue('date_to'));
        return $searchParameters;
    }
    
    protected function setListComponent($leaveList, $count, $page) {
        
        ohrmListComponent::setConfigurationFactory($this->getListConfigurationFactory());
        ohrmListComponent::setActivePlugin('orangehrmLeavePlugin');
        ohrmListComponent::setListData($leaveList);
        ohrmListComponent::setItemsPerPage(sfConfig::get('app_items_per_page'));
        ohrmListComponent::setNumberOfRecords($count);      
        ohrmListComponent::setPageNumber($page);
    }    
    
    protected function getListConfigurationFactory() {
        $configurationFactory = new LeaveEntitlementListConfigurationFactory();
        
        return $configurationFactory;
    }    
}

