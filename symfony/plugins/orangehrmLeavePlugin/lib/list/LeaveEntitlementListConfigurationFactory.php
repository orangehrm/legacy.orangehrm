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
 * Description of LeaveEntitlementListConfigurationFactory
 */
class LeaveEntitlementListConfigurationFactory extends ohrmListConfigurationFactory {
    
    
    public function init() {
        sfContext::getInstance()->getConfiguration()->loadHelpers('OrangeDate');
        
        $header1 = new ListHeader();
        $header2 = new ListHeader();
        $header3 = new ListHeader();
        $header4 = new ListHeader();
        $header5 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Entitlement Type',
            'width' => '40%',
            'isSortable' => false,
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'filters' => array('LeaveEntitlementTypeCellFilter' => array()),  
            'elementProperty' => array('getter' => 'getEntitlementType')
        ));

        $header3->populateFromArray(array(
            'name' => 'Valid From',
            'width' => '25%',
            'isSortable' => false,
            'elementType' => 'labelDate',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => 'getFromDate')
        ));

        $header4->populateFromArray(array(
            'name' => 'Valid To',
            'width' => '25%',
            'isSortable' => false,
            'elementType' => 'labelDate',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => 'getToDate')
        ));
        
        $header5->populateFromArray(array(
            'name' => 'Days',
            'width' => '10%',
            'isSortable' => false,
            'elementType' => 'label',
            'textAlignmentStyle' => 'center',
            'elementProperty' => array('getter' => 'getNoOfDays')
        ));


        $this->headers = array($header1, $header3, $header4, $header5);
    }
    
    public function getClassName() {
        return 'LeaveEntitlement';
    }
}