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

/**
 * Class representing one search filter 
 */
class SearchFilter {
    
    /** Search field */
    private $searchField;
    
    /** Operator */
    private $operator;
    
    /** Qualifier */
    private $qualifier;
    
    /** Search value */
    private $searchValue;

    public function __construct($field, $operator, $searchValue) {
        $this->searchField = $field;
        $this->operator = $operator;
        $this->searchValue = $searchValue;        
    }

    /**
     * Retrieves the value of searchField.
     * @return searchField
     */
    public function getSearchField() {
        return $this->searchField;
    }

    /**
     * Sets the value of searchField.
     * @param searchField
     */
    public function setSearchField($searchField) {
        $this->searchField = $searchField;
    }

    /**
     * Retrieves the value of operator.
     * @return operator
     */
    public function getOperator() {
        return $this->operator;
    }

    /**
     * Sets the value of operator.
     * @param operator
     */
    public function setOperator($operator) {
        $this->operator = $operator;
    }

    /**
     * Retrieves the value of qualifier.
     * @return qualifier
     */
    public function getQualifier() {
        return $this->qualifier;
    }

    /**
     * Sets the value of qualifier.
     * @param qualifier
     */
    public function setQualifier($qualifier) {
        $this->qualifier = $qualifier;
    }

    /**
     * Retrieves the value of searchValue.
     * @return searchValue
     */
    public function getSearchValue() {
        return $this->searchValue;
    }

    /**
     * Sets the value of searchValue.
     * @param searchValue
     */
    public function setSearchValue($searchValue) {
        $this->searchValue = $searchValue;
    }
}
?>

