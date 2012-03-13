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
class SearchSystemUserForm extends BaseForm {

    private $systemUserService;

    public function getSystemUserService() {
        $this->systemUserService = new SystemUserService();
        return $this->systemUserService;
    }

    public function configure() {

        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        
        //merge location filter
        $formExtension  =   PluginFormMergeManager::instance();
        $formExtension->mergeForms($this,'viewSystemUsers','SearchSystemUserForm');

        $this->getWidgetSchema()->setNameFormat('searchSystemUser[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());

        sfWidgetFormSchemaFormatterBreakTags::setNoOfColumns(3);
        $this->getWidgetSchema()->setFormFormatterName('BreakTags');
    }

    /**
     * Get Pre Defined User Role List
     * 
     * @return array
     */
    private function getAssignableUserRoleList() {
        $list = array();
        $list[] = __("All");
        $userRoles = $this->getSystemUserService()->getAssignableUserRoles();
        foreach ($userRoles as $userRole) {
            $list[$userRole->getId()] = $userRole->getDisplayName();
        }
        return $list;
    }

    private function getStatusList() {
        $list = array();
        $list[''] = __("All");
        $list['1'] = __("Enabled");
        $list['0'] = __("Disabled");

        return $list;
    }

    public function getEmployeeListAsJson() {

        $jsonArray = array();
        $employeeService = new EmployeeService();
        $employeeService->setEmployeeDao(new EmployeeDao());

        $employeeList = $employeeService->getEmployeeList();

        $employeeUnique = array();
        foreach ($employeeList as $employee) {
            $workShiftLength = 0;

            if (!isset($employeeUnique[$employee->getEmpNumber()])) {

                $name = $employee->getFullName();

                $employeeUnique[$employee->getEmpNumber()] = $name;
                $jsonArray[] = array('name' => $name, 'id' => $employee->getEmpNumber());
            }
        }

        $jsonString = json_encode($jsonArray);

        return $jsonString;
    }

    public function setDefaultDataToWidgets($searchClues) {
        $this->setDefault('userName', $searchClues['userName']);
        $this->setDefault('userType', $searchClues['userType']);
        $this->setDefault('employeeName', $searchClues['employeeName']);
        $this->setDefault('employeeId', $searchClues['employeeId']);
        $this->setDefault('status', $searchClues['status']);
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $labels = array(
            'userName' => __('Username'),
            'userType' => __('User Type'),
            'employeeName' => __('Employee Name'),
            'status' => __('Status')
        );

        return $labels;
    }

    /**
     *
     * @return array 
     */
    protected function getFormWidgets() {

        $userRoleList = $this->getAssignableUserRoleList();
        $statusList = $this->getStatusList();

        $widgets = array();

        $widgets['userName'] = new sfWidgetFormInputText();
        $widgets['userType'] = new sfWidgetFormSelect(array('choices' => $userRoleList));
        $widgets['employeeName'] = new ohrmWidgetEmployeeNameAutoFill();
        $widgets['employeeId'] = new sfWidgetFormInputHidden();
        $widgets['status'] = new sfWidgetFormSelect(array('choices' => $statusList));

        return $widgets;
    }

    /**
     *
     * @return array 
     */
    protected function getFormValidators() {
        $validators = array();

        $validators['userName'] = new sfValidatorString(array('required' => false));
        $validators['userType'] = new sfValidatorString(array('required' => false));
        $validators['employeeName'] = new sfValidatorString(array('required' => false));
        $validators['employeeName_id'] = new sfValidatorString(array('required' => false));
        $validators['employeeId'] = new sfValidatorString(array('required' => false));
        $validators['status'] = new sfValidatorString(array('required' => false));
        
        return $validators;
    }

}