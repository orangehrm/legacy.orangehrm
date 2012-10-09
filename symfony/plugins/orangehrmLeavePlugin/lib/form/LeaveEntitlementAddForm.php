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
 * Leave Entitlement add form
 */
class LeaveEntitlementAddForm extends LeaveEntitlementForm {
    protected $bulkAssignForm;
    
    public function configure() {
        parent::configure();
        $this->setWidget('id', new sfWidgetFormInputHidden());
        $this->setValidator('id', new sfValidatorNumber(array('required' => false, 'min' => 1)));                
        
        $this->addFilterWidgets();
    

        $this->setWidget('entitlement', new sfWidgetFormInputText());
        $this->setValidator('entitlement', new sfValidatorNumber(array('required' => true)));
        $this->widgetSchema->setLabel('entitlement', __('Entitlement'));
        $this->widgetSchema->setLabel('date_from', __('Entitled from'));
        $this->bulkAssignForm = new LeaveEntitlementBulkAssignFilterForm();             
        $this->embedForm('filter', $this->bulkAssignForm);        
    }    
    
    public function setEditMode() {
        $this->getWidget('leave_type')->setAttribute('disabled', 'disabled');
        $this->getWidget('employee')->setAttribute('disabled', 'disabled');
        $this->setValidator('employee', new sfValidatorPass());
        $this->setValidator('leave_type', new sfValidatorPass());           
    }
    
    public function addFilterWidgets() {
        
    }
    
    public function render($attributes = array()) {
        
        //$html = $this->bulkAssignForm->render($attributes);
        $html = parent::render($attributes);
        return $html;
    }
}
