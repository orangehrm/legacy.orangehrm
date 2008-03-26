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
 
class SearchField {

    /** Field type constants */
    const FIELD_TYPE_STRING = 'string';
    const FIELD_TYPE_INT = 'int';
    const FIELD_TYPE_SELECT = 'select';
    const FIELD_TYPE_DATE = 'date';
    
    /** Comparison type constants */
    const COMPARISON_TYPE_LESSTHAN = 'lt';
    const COMPARISON_TYPE_GREATERTHAN = 'gt';
    const COMPARISON_TYPE_EQUAL = 'eq';
    const COMPARISON_TYPE_STARTSWITH = 'starts';
    const COMPARISON_TYPE_ENDSWITH = 'ends';
    const COMPARISON_TYPE_CONTAINS = 'contains';
    
    /** Search qualifiers */
    const QUALIFIER_CASE_SENSITIVE = 'cs';
    const QUALIFIER_CASE_INSENSITIVE = 'ci';
    
    /** The name of the field */
    private $fieldName;
    
    /**
     *  The language variable containing the field's display name
     * eg: lang_employee_name 
     */
    private $displayNameVar;
    
    /** Field type, one of the FIELD_TYPE constants */
    private $fieldType;
    
    /** Array of select options. Only used for FIELD_TYPE_SELECT */
    private $selectOptions;
    
    /** Array of possible comparisons that can be used */
    private $comparisons;

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

    /**
     * Retrieves the value of fieldType.
     * @return fieldType
     */
    public function getFieldType() {
        return $this->fieldType;
    }

    /**
     * Sets the value of fieldType.
     * @param fieldType
     */
    public function setFieldType($fieldType) {
        $this->fieldType = $fieldType;
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

    /**
     * Retrieves the value of comparisons.
     * @return comparisons
     */
    public function getComparisons() {
        return $this->comparisons;
    }

    /**
     * Sets the value of comparisons.
     * @param comparisons
     */
    public function setComparisons($comparisons) {
        $this->comparisons = $comparisons;
    }
}

?>
