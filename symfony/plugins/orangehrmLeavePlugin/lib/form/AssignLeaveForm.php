<?php

/*
 *
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
 * Assign Leave form class
 */
class AssignLeaveForm extends sfForm {

    protected $leavePeriodService;    
    protected $configService;


    public function getConfigService() {
        
        if (!$this->configService instanceof ConfigService) {
            $this->configService = new ConfigService();
        }
        
        return $this->configService;        
    }

    public function setConfigService($configService) {
        $this->configService = $configService;
    }  
    
    public function getLeavePeriodService() {
        
        if (is_null($this->leavePeriodService)) {
            $this->leavePeriodService = new LeavePeriodService();
        }
        return $this->leavePeriodService;
    }

    public function setLeavePeriodService($leavePeriodService) {
        $this->leavePeriodService = $leavePeriodService;
    }
    
    /**
     * Configure Form
     *
     */
    public function configure() {

        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());

        $this->setDefault('leaveBalance', '--');

        $this->getValidatorSchema()->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'postValidation'))));

        $this->getWidgetSchema()->setNameFormat('assignleave[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setFormFormatterName('ListFields');
    }

    /**
     *
     * @return array
     */
    protected function getLeaveTypeChoices() {

        $leaveTypeList = $this->getOption('leaveTypes');
        $leaveTypeChoices = array('' => '--' . __('Select') . '--');

        foreach ($leaveTypeList as $leaveType) {
            $leaveTypeChoices[$leaveType->getId()] = $leaveType->getName();
        }

        return $leaveTypeChoices;
    }

    /**
     * Post validation
     * @param $validator
     * @param $values
     * @return unknown_type
     */
    public function postValidation($validator, $values) {

        $errorList = array();

        $fromDateTimeStamp = strtotime($values['txtFromDate']);
        $toDateTimeStamp = strtotime($values['txtToDate']);

        $fromTime = $values['time']['from'];
        $fromTimetimeStamp = strtotime($fromTime);
        
        $toTime = $values['time']['to'];
        $toTimetimeStamp = strtotime($toTime);

        if ((is_int($fromDateTimeStamp) && is_int($toDateTimeStamp)) && ($toDateTimeStamp - $fromDateTimeStamp) < 0) {
            $errorList['txtFromDate'] = new sfValidatorError($validator, ' From date should be a before to date');
        }


        if (($values['txtFromDate'] == $values['txtToDate']) && (is_int($fromTimetimeStamp) && is_int($toTimetimeStamp))
                && ($toTimetimeStamp - $fromTimetimeStamp) < 0) {
            $errorList['time'] = new sfValidatorError($validator, ' From time should be before to time');
        }
        
        $maxDate = $this->getLeaveAssignDateLimit();
        $maxTimeStamp = strtotime($maxDate);
        
        if (is_int($toDateTimeStamp) && ($toDateTimeStamp > $maxTimeStamp)) {
            $errorList['txtToDate'] = new sfValidatorError($validator, __('Cannot assign leave beyond ') . $maxDate);
        }           

        if (count($errorList) > 0) {

            throw new sfValidatorErrorSchema($validator, $errorList);
        }     
        
        $values['txtFromDate'] = date('Y-m-d', $fromDateTimeStamp);
        $values['txtToDate'] = date('Y-m-d', $toDateTimeStamp);
        $values['txtLeaveTotalTime'] = $this->getWidget('time')->getTimeDifference($fromTime, $toTime);

        return $values;
    }
    
    protected function getLeaveAssignDateLimit() {
        // If leave period is defined (enforced or not enforced), don't allow apply assign beyond next Leave period
        // If no leave period, don't allow apply/assign beyond next calender year
        $todayNextYear = new DateTime();
        $todayNextYear->add(new DateInterval('P1Y'));
            
        if ($this->getConfigService()->isLeavePeriodDefined()) {
            $period = $this->getLeavePeriodService()->getCurrentLeavePeriodByDate($todayNextYear->format('Y-m-d'));
            $maxDate = $period[1];
        } else {
            $nextYear = $todayNextYear->format('Y');
            $maxDate = $nextYear . '-12-31';
        }        
        
        return $maxDate;
    }

    protected function getEmployeeListAsJson() {

        $jsonArray = array();

        $properties = array("empNumber", "firstName", "middleName", "lastName", 'termination_id');

        $requiredPermissions = array(
            BasicUserRoleManager::PERMISSION_TYPE_ACTION => array('assign_leave'));

        $employeeList = UserRoleManagerFactory::getUserRoleManager()
                ->getAccessibleEntityProperties('Employee', $properties, null, null, array(), array(), $requiredPermissions);

        $employeeUnique = array();
        foreach ($employeeList as $employee) {
            $terminationId = $employee['termination_id'];
            $empNumber = $employee['empNumber'];
            if (!isset($employeeUnique[$empNumber]) && empty($terminationId)) {
                $name = trim(trim($employee['firstName'] . ' ' . $employee['middleName'], ' ') . ' ' . $employee['lastName']);

                $employeeUnique[$empNumber] = $name;
                $jsonArray[] = array('name' => $name, 'id' => $empNumber);
            }
        }

        $jsonString = json_encode($jsonArray);

        return $jsonString;
    }

    /**
     *
     * @return array
     */
    public function getStylesheets() {
        $styleSheets = parent::getStylesheets();

        return $styleSheets;
    }

    /**
     *
     * @return array
     */
    protected function getFormWidgets() {

        $widgets = array(
            'txtEmployee' => new ohrmWidgetEmployeeNameAutoFill(array('jsonList' => $this->getEmployeeListAsJson())),
            'txtEmpWorkShift' => new sfWidgetFormInputHidden(),
            'txtLeaveType' => new sfWidgetFormChoice(array('choices' => $this->getLeaveTypeChoices())),
            'leaveBalance' => new ohrmWidgetDiv(array(), array('style' => 'float:left;padding-top: 6px;')),
            'txtFromDate' => new ohrmWidgetDatePicker(array(), array('id' => 'assignleave_txtFromDate')),
            'txtToDate' => new ohrmWidgetDatePicker(array(), array('id' => 'assignleave_txtToDate')),
            'time' => new ohrmWidgetFormTimeRange(array(
                    'from_time' => new ohrmWidgetTimeDropDown(),
                    'to_time' => new ohrmWidgetTimeDropDown())
            ),
            'txtComment' => new sfWidgetFormTextarea(array(), array('rows' => '3', 'cols' => '30')),
        );

        return $widgets;
    }

    /**
     *
     * @return array
     */
    protected function getFormValidators() {
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $leaveTypeIds = array_keys($this->getLeaveTypeChoices());        
        $fromTimeWidget = $this->getWidget('time')->getOption('from_time');    
        $timeChoices = $fromTimeWidget->getChoices();
        
        $validators = array(
            'txtEmployee' => new ohrmValidatorEmployeeNameAutoFill(),
            'txtEmpWorkShift' => new sfValidatorString(array('required' => false)),
            'txtLeaveType' => new sfValidatorChoice(array('choices' => $leaveTypeIds, 'required' => true)),
            'txtFromDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'txtToDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'txtComment' => new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 1000)),
            'time' => new sfValidatorPass()
        );

        return $validators;
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $requiredMarker = ' <em>*</em>';

        $labels = array(
            'txtEmployee' => __('Employee Name') . $requiredMarker,
            'txtLeaveType' => __('Leave Type') . $requiredMarker,
            'leaveBalance' => __('Leave Balance'),
            'txtFromDate' => __('From Date') . $requiredMarker,
            'txtToDate' => __('To Date') . $requiredMarker,
            'txtComment' => __('Comment'),
        );

        return $labels;
    }

}

