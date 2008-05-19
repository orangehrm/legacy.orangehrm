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
 * Ruchira
 */

require_once ROOT_PATH . '/lib/common/search/DisplayField.php';
require_once ROOT_PATH . '/lib/common/search/SearchOperator.php';
 
class EditableField extends DisplayField {
                
    /** Array of select options. Only used for FIELD_TYPE_SELECT */
    protected $selectOptions;
    
    /**
     * Constructor
     * 
     * @param String $fieldName
     * @param String $displayNameVar
     * @param String $fieldType Field type
     * @param Array $selectOptions Array of select options
     */
    public function __construct($fieldName, $displayNameVar, $fieldType, $selectOptions = null) {
        
        parent::__construct($fieldName, $displayNameVar, $fieldType);
        
        $this->selectOptions = $selectOptions;        
    }
    
    
    /**
     * Retrieves the value of selectOptions.
     * @return selectOptions
     */
    public function getSelectOptions() {
        return $this->selectOptions;
    }

    /**
     * Sets the value of selectOptions.
     * @param selectOptions
     */
    public function setSelectOptions($selectOptions) {
        $this->selectOptions = $selectOptions;
    }

}

?>
