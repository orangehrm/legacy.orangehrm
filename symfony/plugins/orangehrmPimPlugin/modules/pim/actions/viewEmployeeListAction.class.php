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
 * View employee list action
 */
class viewEmployeeListAction extends basePimAction {

    /**
     * Index action. Displays employee list
     *      `
     * @param sfWebRequest $request
     */
    public function execute($request) {

        // Check if admin mode or supervisor mode
        $userType = 'Admin';
        $this->adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);
        
        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        if (!$this->adminMode) {
            $this->supervisorMode = $this->getUser()->hasCredential(Auth::SUPERVISOR_ROLE);
            $userType = 'Supervisor';
        } else {
            $this->supervisorMode = false;
        }

        if (!$this->adminMode && !$this->supervisorMode) {
            return $this->forward("pim", "unauthorized");
        }
        
        $this->mode = $mode = $this->getMode();
        
        $empNumber = $request->getParameter('empNumber');
        $isPaging = $request->getParameter('pageNo');

        $pageNumber = $isPaging;
        if (!empty($empNumber) && $this->getUser()->hasAttribute('pageNumber')) {
            $pageNumber = $this->getUser()->getAttribute('pageNumber');
        }

        $sortField = $request->getParameter('sortField');
        $sortOrder = $request->getParameter('sortOrder');

        $noOfRecords = JobTitle::NO_OF_RECORDS_PER_PAGE;
        $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;

         // Reset filters if requested to
        if ($request->hasParameter('reset')) {
            $this->setFilters(array());
            $this->setPage(1);
        }

        $params = array('userType'=> $userType, 'loggedInUserId'=>$this->getUser()->getEmployeeNumber());
        $this->form = new EmployeeSearchForm($this->getFilters(), $params);
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $this->setFilters($this->form->getValues());
            } else {
                $this->setFilters(array());
            }

            $this->setPage(1);
        }

        $filters = $this->getFilters();
        $filters['employee_name'] = str_replace(' (' . __('Past Employee') . ')', '', $filters['employee_name']);        
        
        $this->filterApply = !empty($filters);


        if ($this->supervisorMode) {
            $filters['supervisorId'] = $this->getUser()->getEmployeeNumber();
        }

        $table = Doctrine::getTable('Employee');
        $count = $table->getEmployeeCount($filters);

        $list = $table->getEmployeeList($sortField, $sortOrder, $filters, $offset, $noOfRecords);
        $this->setListComponent($list, $count, $noOfRecords, $pageNumber);

        // Show message if list is empty, and we don't already have a message.
        if (empty($this->message) && (count($list) == 0)) {

            // Check to see if we have any employees in system
            $employeeCount = $this->getEmployeeService()->getEmployeeCount();
            $this->messageType = "warning";

            if (empty($employeeCount)) {
                $this->message = __("No Employees Available");
            } else {
                $this->message = __(TopLevelMessages::NO_RECORDS_FOUND);
            }

        }
    }
    
    protected function setListComponent($employeeList, $count, $noOfRecords, $page) {
        
        ohrmListComponent::setConfigurationFactory($this->getListConfigurationFactory());
        ohrmListComponent::setActivePlugin('orangehrmPimPlugin');
        ohrmListComponent::setListData($employeeList);
//        ohrmListComponent::setItemsPerPage(sfConfig::get('app_items_per_page'));
        ohrmListComponent::setItemsPerPage($noOfRecords);
        ohrmListComponent::setNumberOfRecords($count);      
        ohrmListComponent::setPageNumber($page);
    }
    
    protected function getListConfigurationFactory() {
        EmployeeListConfigurationFactory::setListMode($this->mode);
        $configurationFactory = new EmployeeListConfigurationFactory();
        
        return $configurationFactory;
    }
    
    
    protected function getMode() {
        $mode ='';
        
        if ($this->adminMode) {
            $mode = 'adminMode';
        } else if($this->supervisorMode) {
            $mode = 'supervisorMode';
        }
        
        return $mode;
    }

    /**
     * Set's the current page number in the user session.
     * @param $page int Page Number
     * @return None
     */
    protected function setPage($page) {
        $this->getUser()->setAttribute('emplist.page', $page, 'pim_module');
    }

    /**
     * Get the current page number from the user session.
     * @return int Page number
     */
    protected function getPage() {
        return $this->getUser()->getAttribute('emplist.page', 1, 'pim_module');
    }

    /**
     *
     * @param array $filters
     * @return unknown_type
     */
    protected function setFilters(array $filters) {
        return $this->getUser()->setAttribute('emplist.filters', $filters, 'pim_module');
    }

    /**
     *
     * @return unknown_type
     */
    protected function getFilters() {
        return $this->getUser()->getAttribute('emplist.filters', null, 'pim_module');
    }

    protected function _getFilterValue($filters, $parameter, $default = null) {
        $value = $default;
        if (isset($filters[$parameter])) {
            $value = $filters[$parameter];
        }

        return $value;
    }

}
