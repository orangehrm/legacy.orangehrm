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
class LeaveBalanceReportForm extends BaseForm {
    
    
    public function configure() {

        $reportTypes = array(0 => 'Please Select', 
                             3 => 'Leave Type', 
                             2 => 'Employee');
        
        // Valid report types, skip 0 option
        $validReportTypes = array_slice(array_keys($reportTypes), 1);
        
        $this->setWidget('report_type', new sfWidgetFormChoice(array('choices' => $reportTypes)));
        $this->setValidator('report_type', new sfValidatorChoice(array('choices' => $validReportTypes),
                array('invalid' => CommonMessages::REQUIRED_FIELD,
                      'required' => true)));        
           
        $this->setWidget('date', new ohrmWidgetFormDateRange(array(
                    'from_date' => new ohrmWidgetDatePicker(array(), array('id' => 'date_from')),
                    'to_date' => new ohrmWidgetDatePicker(array(), array('id' => 'date_to')))
                ));
        

        $this->setValidator('date', new sfValidatorDateRange(array(
            'from_date' => new ohrmDateValidator(array('required' => true)),
            'to_date' => new ohrmDateValidator(array('required' => true))
        )));
        
        $formExtension = PluginFormMergeManager::instance();
        $formExtension->mergeForms($this, 'viewLeaveBalanceReport', 'LeaveBalanceReportForm');
  
        $this->widgetSchema->setNameFormat('leave_balance[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setFormFormatterName('ListFields');
    }
    
    protected function getFormLabels() {

        $labels = array(
            'report_type' => __('Generate For'),
            'date' => 'From'
        );
        return $labels;
    }
    
}

