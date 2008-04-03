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

require_once ROOT_PATH . '/lib/common/search/SearchOperator.php';
 
class SearchField {
    
    /** Field type constants */
    const FIELD_TYPE_STRING = 'string';
    const FIELD_TYPE_INT = 'int';
    const FIELD_TYPE_SELECT = 'select';
    const FIELD_TYPE_DATE = 'date';
        
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
    
    /** Array of possible operators that can be used */
    private $operators;

    /**
     * Constructor
     * 
     * @param String $fieldName
     * @param String $displayNameVar
     * @param String $fieldType Field type
     * @param Array $operators Array of operators supported by this field. If not given, default options
     *                           for this fieldType will be used
     * @param Array $selectOptions Array of select options
     */
    public function __construct($fieldName, $displayNameVar, $fieldType, $operators = null, $selectOptions = null) {
        $this->fieldName = $fieldName;
        $this->displayNameVar = $displayNameVar;
        $this->fieldType = $fieldType;
        
        if (empty($operators)) {
            $this->operators = $this->_getDefaultOperators($fieldType);    
        } else {
            $this->operators = $operators;
        }
        
        $this->selectOptions = $selectOptions;        
    }
    
    /**
     * Get default operators for the given field type
     * 
     * @param String $fieldType Field type constant
     * @return Array Array of SearchOperator objects
     */
    private function _getDefaultOperators($fieldType) {
    
        $operators = array();
        
        switch ($fieldType) {
            case self::FIELD_TYPE_STRING:
                $operators = array(SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL),
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EQUAL),
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_STARTSWITH),
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_ENDSWITH),
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_CONTAINS),
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_CONTAINS));                
                break;
            case self::FIELD_TYPE_INT:
                $operators = array(SearchOperator::getOperator(SearchOperator::OPERATOR_LESSTHAN), 
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_GREATERTHAN),
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL),
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EQUAL));
                break;
            case self::FIELD_TYPE_DATE:
                $operators = array(SearchOperator::getOperator(SearchOperator::OPERATOR_LESSTHAN), 
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_GREATERTHAN),
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL),
                                     SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EQUAL));
                break;
            case self::FIELD_TYPE_SELECT:
                $operators = array(SearchOperator::getOperator(SearchOperator::OPERATOR_EQUAL),
                                   SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EQUAL),
                                   SearchOperator::getOperator(SearchOperator::OPERATOR_EMPTY),
                                   SearchOperator::getOperator(SearchOperator::OPERATOR_NOT_EMPTY));
                break;
            default:
                break;
        }
        
        return $operators;
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
     * Retrieves the value of operators.
     * @return operators
     */
    public function getOperators() {
        return $this->operators;
    }

    /**
     * Sets the value of operators.
     * @param operators
     */
    public function setOperators($operators) {
        $this->operators = $operators;
    }
}

?>
