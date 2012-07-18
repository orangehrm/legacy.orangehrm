<?php

/*
  // OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
  // all the essential functionalities required for any enterprise.
  // Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com

  // OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
  // the GNU General Public License as published by the Free Software Foundation; either
  // version 2 of the License, or (at your option) any later version.

  // OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
  // without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
  // See the GNU General Public License for more details.

  // You should have received a copy of the GNU General Public License along with this program;
  // if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
  // Boston, MA  02110-1301, USA
 */

/**
 * Form class for employee membership detail
 */
class EmployeeReportToForm extends BaseForm {

    public $fullName;
    public $empNumber;
    private $employeeService;
    private $reportingMethodService;
    private $reportingMethodType;

    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {

        if (is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    /**
     * Set EmployeeService
     * @param EmployeeService $employeeService
     */
    public function setEmployeeService(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }
    
    public function getReportingMethodService() {

        if (is_null($this->reportingMethodService)) {
            $this->reportingMethodService = new ReportingMethodService();
        }
        
        return $this->reportingMethodService;
        
    }    
    
    public function setReportingMethodService(ReportingMethod $reportingMethodService) {
        $this->reportingMethodService = $reportingMethodService;
    }

    public function configure() {
        $this->reportToPermissions = $this->getOption('reportToPermissions');

        $this->reportingMethodType = $this->getReportingMethodType();

        $this->empNumber = $this->getOption('empNumber');
        $employee = $this->getEmployeeService()->getEmployee($this->empNumber);
        $this->fullName = $employee->getFullName();
        
        $widgets = array('empNumber' => new sfWidgetFormInputHidden(array(), array('value' => $this->empNumber)));
        $validators = array('empNumber' => new sfValidatorString(array('required' => true)));
        
        if ($this->reportToPermissions->canRead()) {

            $reportToWidgets = $this->getRepoertToWidgets();
            $reportToValidators = $this->getRepoertToValidators();

            if (!($this->reportToPermissions->canUpdate() || $this->reportToPermissions->canCreate()) ) {
                foreach ($dependentWidgets as $widgetName => $widget) {
                    $widget->setAttribute('disabled', 'disabled');
                }
            }
            $widgets = array_merge($widgets, $reportToWidgets);
            $validators = array_merge($validators, $reportToValidators);
        }

        $this->setWidgets($widgets);
        $this->setValidators($validators);
        
        $this->widgetSchema->setNameFormat('reportto[%s]');
    }
    
    /*
     * Tis fuction will return the widgets of the form
     */
    public function getRepoertToWidgets(){
        $widgets = array();
        
        //creating widgets
        $widgets['type_flag'] = new sfWidgetFormChoice(array('expanded' => true, 'choices' => array(
                    ReportTo::SUPERVISOR => __('Supervisor'), ReportTo::SUBORDINATE => __('Subordinate')), 'default' => ReportTo::SUPERVISOR));
        $widgets['name'] = new ohrmWidgetEmployeeNameAutoFill(array('employeeList' => $this->getEmployeeList()));
        $widgets['previousRecord'] = new sfWidgetFormInputHidden();
        $widgets['reportingMethodType'] = new sfWidgetFormSelect(array('choices' => $this->reportingMethodType));
        $widgets['reportingMethod'] = new sfWidgetFormInputText();
        
        return $widgets;
    }
    
    
    /*
     * Tis fuction will return the form validators
     */
    public function getRepoertToValidators(){
        
        $validators = array(
            'type_flag' => new sfValidatorChoice(array('required' => true,
                'choices' => array(ReportTo::SUPERVISOR, ReportTo::SUBORDINATE))),
            'name' => new ohrmValidatorEmployeeNameAutoFill(),
            'name_id' => new sfValidatorString(array('required' => false)),
            'previousRecord' => new sfValidatorString(array('required' => false)),
            'reportingMethodType' => new sfValidatorString(array('required' => true), array('required' => 'Select reporting method')),
            'reportingMethod' => new sfValidatorString(array('required' => false, 'max_length' => 80)),
        );
        
        return $validators;
    }

    /**
     * Returns Reporting method Type
     * @return array
     */
    private function getReportingMethodType() {

        $list = array("" => "-- " . __('Select') . " --");

        $reportingMethodTypes = $this->getReportingMethodService()->getReportingMethodList();

        foreach ($reportingMethodTypes as $reportingMethodType) {
            $list[$reportingMethodType->id] = $reportingMethodType->name;
        }
        $list[-1] = __('Other');
        return $list;
    }

    protected function getEmployeeList() {

        $employeeService = $this->getEmployeeService();
       
        $employeeList = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntities('Employee');
        
        $finalEmployeeList = array();

        /* Populating already assigned sup & sub */
        $assignedReportTo = array();
        $supervisors = $employeeService->getSupervisorListForEmployee($this->empNumber);
        $subordinates = $employeeService->getSubordinateListForEmployee($this->empNumber);

        foreach ($subordinates as $subordinate) {
            $assignedReportTo[$subordinate->getSubordinateId()] = true;
        }
        
        foreach ($supervisors as $supervisor) {
            $assignedReportTo[$supervisor->getSupervisorId()] = true;
        }

        /* Populating final list */
        foreach ($employeeList as $employee) {

            if (!isset($assignedReportTo[$employee->getEmpNumber()]) && 
                $employee->getEmpNumber() != $this->empNumber) {

                $finalEmployeeList[] = $employee;                
                
            }
        }

        return $finalEmployeeList;
        
    }
    
    /**
     * Save supervisors and subordinates
     */
    public function save() {

        $updated = false;
        $empNumber = $this->getValue('empNumber');
        $supOrSub = $this->getValue('type_flag');
        $empData = $this->getValue('name');
        $name = $empData['empName'];
        $reportingType = $this->getValue('reportingMethodType');
        $reportingMethod = $this->getValue('reportingMethod');
        $selectedEmployee = $empData['empId'];
        $previousRecord = $this->getValue('previousRecord');

        if ($reportingMethod != null) {

            $newReportingMethod = new ReportingMethod();
            $newReportingMethod->name = $reportingMethod;
            $savedReportingMethod = $this->getReportingMethodService()->saveReportingMethod($newReportingMethod);
            $reportingType = $savedReportingMethod->id;
        }

        if ($supOrSub == ReportTo::SUPERVISOR) {
            $existingReportToObject = $this->getEmployeeService()->getReportToObject($selectedEmployee, $empNumber);

            if ($existingReportToObject != null) {
                $existingReportToObject->setReportingMethodId($reportingType);
                $existingReportToObject->save();
            } else {
                $newReportToObject = new ReportTo();
                $newReportToObject->setSupervisorId($selectedEmployee);
                $newReportToObject->setSubordinateId($empNumber);
                $newReportToObject->setReportingMethodId($reportingType);
                $newReportToObject->save();
            }
        }

        if ($supOrSub == ReportTo::SUBORDINATE) {
            $existingReportToObject = $this->getEmployeeService()->getReportToObject($empNumber, $selectedEmployee);

            if ($existingReportToObject != null) {
                $existingReportToObject->setReportingMethodId($reportingType);
                $existingReportToObject->save();
            } else {
                $newReportToObject = new ReportTo();
                $newReportToObject->setSupervisorId($empNumber);
                $newReportToObject->setSubordinateId($selectedEmployee);
                $newReportToObject->setReportingMethodId($reportingType);
                $newReportToObject->save();
            }
        }
        $returnValue = array($supOrSub, $updated);
        return $returnValue;
    }

}

