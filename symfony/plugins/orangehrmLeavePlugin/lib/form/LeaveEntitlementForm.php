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
 * Form class for leave entitlements screen
 *
 */
class LeaveEntitlementForm extends BaseForm {

    protected $leaveTypeService;
    
    public function getLeaveTypeService() {
        if (!isset($this->leaveTypeService)) {
            $this->leaveTypeService = new LeaveTypeService();
        }
        return $this->leaveTypeService;
    }

    public function setLeaveTypeService(LeaveTypeService $leaveTypeService) {
        $this->leaveTypeService = $leaveTypeService;
    }

    
    public function configure() {

        $this->setWidgets(array(
            'employee' => new ohrmWidgetEmployeeNameAutoFill(array('loadingMethod'=>'ajax')),
        ));

        $this->setValidator('employee', new ohrmValidatorEmployeeNameAutoFill());

        $this->_setLeaveTypeWidget();
        
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        
        $this->setWidget('date_from', new ohrmWidgetDatePicker(array(), array('id' => 'date_from')));
        $this->setValidator('date_from', new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true)));

        $this->setWidget('date_to', new ohrmWidgetDatePicker(array(), array('id' => 'date_to')));
        $this->setValidator('date_to', new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true)));
                
        $formExtension = PluginFormMergeManager::instance();
        $formExtension->mergeForms($this, 'viewLeaveEntitlements','LeaveEntitlementsForm');

        
        $this->widgetSchema->setNameFormat('entitlements[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setFormFormatterName('ListFields');

    }

    private function _setLeaveTypeWidget() {

        $choices = array('' => '--' . __('Select') . '--');
        
        $leaveTypeList = $this->getLeaveTypeService()->getLeaveTypeList();
        
        foreach ($leaveTypeList as $leaveType) {
            $choices[$leaveType->getLeaveTypeId()] = $leaveType->getLeaveTypeName();
        }

        $this->setWidget('leave_type', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->setValidator('leave_type', new sfValidatorChoice(array('choices' => array_keys($choices))));
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {

        $labels = array(
            'employee' => __('Employee'),
            'leave_type' => __('Leave Type'),
            'date_earned' => __('Earned')
        );
        return $labels;
    }

}

