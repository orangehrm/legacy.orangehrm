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
 
class DisplayField {
   
    /** The name of the field */
    private $fieldName;
    
    /**
     *  The language variable containing the field's display name
     * eg: lang_employee_name 
     */
    private $displayNameVar;
    
    /** Field type, one of the FIELD_TYPE constants */
    private $fieldType;

    /**
     * Constructor
     * 
     * @param String $fieldName
     * @param String $displayNameVar
     */
    public function __construct($fieldName, $displayNameVar) {
        $this->fieldName = $fieldName;
        $this->displayNameVar = $displayNameVar;
    }
    
    /**
     * Retrieves the value of fieldName.
     * @return fieldName
     */
    public function getFieldName() {
        return $this->fieldName;
    }

    /**
     * Sets the value of fieldName.
     * @param fieldName
     */
    public function setFieldName($fieldName) {
        $this->fieldName = $fieldName;
    }

    /**
     * Retrieves the value of displayNameVar.
     * @return displayNameVar
     */
    public function getDisplayNameVar() {
        return $this->displayNameVar;
    }

    /**
     * Sets the value of displayNameVar.
     * @param displayNameVar
     */
    public function setDisplayNameVar($displayNameVar) {
        $this->displayNameVar = $displayNameVar;
    }
}

?>
