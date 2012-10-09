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
    
    const FILTERS_ATTRIBUTE_NAME = 'entitlementlist.filters';
    
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
    
    protected function getForm() {
        return new LeaveEntitlementForm();
    }
    
    protected function showResultTableByDefault() {
        return false;
    }
    
    protected function getTitle() {
        return 'Leave Entitlements';
    }
    
    protected function getDefaultFilters() {
        return $this->form->getDefaults();
    }
    
    public function execute($request) {        
        
        $this->title = $this->getTitle();
        $this->form = $this->getForm();

        $this->showResultTable = $this->showResultTableByDefault();
        
        $filters = $this->getDefaultFilters();
        
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $this->showResultTable = true;
                $filters = $this->form->getValues();                
                $this->saveFilters($filters);                       
            }
        } else if ($request->hasParameter('savedsearch')) {
            $filters = $this->getFilters();            
            $this->showResultTable = true;

            $this->form->setDefaults($filters);
        } else {
            $this->saveFilters(array());
        }
        
        if ($this->showResultTable) {
            $searchParameters = $this->getSearchParameterObject($filters);
            $empNumber = $searchParameters->getEmpNumber();
            if (empty($empNumber)) {
                $this->showResultTable = false;
            } else {
                $results = $this->getLeaveEntitlementService()->searchLeaveEntitlements($searchParameters);
                $this->setListComponent($results, 0, 0);        
            }
        }
    }
    
    protected function getSearchParameterObject($filters) {
        $searchParameters = new LeaveEntitlementSearchParameterHolder();
        $employeeName = $filters['employee'];
        $id = $employeeName['empId'];
        
        $userRoleManager = $this->getContext()->getUserRoleManager();
        $isAccessible = $userRoleManager->isEntityAccessible('Employee', $id);
        if (!empty($id)) {
            if ($isAccessible || ($this->getUser()->getAttribute('auth.empNumber') == $id)) {        
                $searchParameters->setEmpNumber($id);
            } else {
                $this->getUser()->setFlash('warning', 'Access Denied to Selected Employee');
                $this->redirect('leave/viewLeaveEntitlements');
            }
        }
        $searchParameters->setLeaveTypeId($filters['leave_type']);
        $searchParameters->setFromDate($filters['date_from']);
        $searchParameters->setToDate($filters['date_to']);
        return $searchParameters;
    }
    
    protected function setListComponent($leaveList, $count, $page) {
        
        $configurationFactory = $this->getListConfigurationFactory();

        $permissions = $this->getContext()->get('screen_permissions');
        
        $runtimeDefinitions = array();
        $buttons = array();

        if ($permissions->canCreate()) {
            $buttons['Add'] = array('label' => 'Add');
        }
        if (!$permissions->canDelete()) {
            $runtimeDefinitions['hasSelectableRows'] = false;
        } else {
            $buttons['Delete'] = array(
                'label' => 'Delete',
                'type' => 'submit',
                'data-toggle' => 'modal',
                'data-target' => '#deleteConfModal',
                'class' => 'delete');
        }
        
        $configurationFactory->setAllowEdit($permissions->canUpdate());

        $runtimeDefinitions['buttons'] = $buttons;
        $configurationFactory->setRuntimeDefinitions($runtimeDefinitions);
        
        ohrmListComponent::setConfigurationFactory($configurationFactory);
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
    
    /**
     * Save search filters as user attribute
     * @param array $filters
     */
    protected function saveFilters(array $filters) {
        $this->getUser()->setAttribute(self::FILTERS_ATTRIBUTE_NAME, $filters, 'leave');
    }    
    
    /**
     * Get search filters from user attribute
     * @param array $filters
     * @return array
     */
    protected function getFilters() {
        return $this->getUser()->getAttribute(self::FILTERS_ATTRIBUTE_NAME, null, 'leave');
    }        
}

