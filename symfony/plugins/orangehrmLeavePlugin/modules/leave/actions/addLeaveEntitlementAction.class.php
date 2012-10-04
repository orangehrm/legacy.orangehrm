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
 * Description of addLeaveEntitlementAction
 */
class addLeaveEntitlementAction extends sfAction {
    
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
        
        $this->addMode = true;
        $this->form = $this->getForm();
        
        if ($request->hasParameter('id')) {                
            $id = $request->getParameter('id');
            $filters = $this->getFiltersFromEntitlement($id);  
            $this->addMode = false;
            $this->form->setEditMode();
        }       
        
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $leaveEntitlement = $this->getLeaveEntitlement($this->form->getValues());
                $this->getLeaveEntitlementService()->saveLeaveEntitlement($leaveEntitlement);
                
                $successMessage = $this->addMode ?  TopLevelMessages::ADD_SUCCESS : TopLevelMessages::UPDATE_SUCCESS;
                $this->getUser()->setFlash('success', __($successMessage));
                $this->redirect('leave/viewLeaveEntitlements?savedsearch=1');
            }
        } else {
            if ($request->hasParameter('id')) {                
                $id = $request->getParameter('id');
                $filters = $this->getFiltersFromEntitlement($id);  
            } else {
                $filters = $this->getFilters();
            }
            
            if (!empty($filters)) {
                $this->form->setDefaults($filters);
            }            
        }        
    }
    
    protected function getFiltersFromEntitlement($id) {
        $entitlement = $this->getLeaveEntitlementService()->getLeaveEntitlement($id);    
        
        $employee = $entitlement->getEmployee();        
        
        $filters = array(
            'id' => $id,
            'employee' => array(
                'empName' => $employee->getFirstAndLastNames(),
                'empId' => $employee->getEmpNumber()),
            'leave_type' => $entitlement->getLeaveTypeId(),
            'date_from' => set_datepicker_date_format($entitlement->getFromDate()),
            'date_to' => set_datepicker_date_format($entitlement->getToDate()),
            'entitlement' => $entitlement->getNoOfDays()
        );
        
        return $filters;
    }
    
    protected function getForm() {
        return new LeaveEntitlementAddForm();
    }
    
    /**
     * Get search filters from user attribute
     * @param array $filters
     * @return array
     */
    protected function getFilters() {
        return $this->getUser()->getAttribute(viewLeaveEntitlementsAction::FILTERS_ATTRIBUTE_NAME, array(), 'leave');
    }  
    
    protected function getLeaveEntitlement($values) {
        
        if (isset($values['id'])) {
            $id = $values['id'];
            $leaveEntitlement = $this->getLeaveEntitlementService()->getLeaveEntitlement($id);
        } else {
            $leaveEntitlement = new LeaveEntitlement();  
            
            $leaveEntitlement->setEmpNumber($values['employee']['empId']);
            $leaveEntitlement->setLeaveTypeId($values['leave_type']);
        
            $user = $this->getUser();
            $userId = $user->getAttribute('auth.userId');
            $createdBy = $this->getUser()->getAttribute('auth.firstName');
            
            $leaveEntitlement->setCreditedDate(date('Y-m-d'));
            $leaveEntitlement->setCreatedById($userId);
            $leaveEntitlement->setCreatedByName($createdBy);        
            
            $leaveEntitlement->setEntitlementType(LeaveEntitlement::ENTITLEMENT_TYPE_ADD);
            $leaveEntitlement->setDeleted(0);            
        }
        
        $leaveEntitlement->setNoOfDays($values['entitlement']);
        $leaveEntitlement->setFromDate($values['date_from']);
        $leaveEntitlement->setToDate($values['date_to']);

        return $leaveEntitlement;
    }
}
