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

abstract class AbstractSearch {

    /** Match */
    const MATCH_ALL = 'matchAll';
    const MATCH_ANY = 'matchAny';
    
    /** Sort Type */
    const SORT_ASCENDING = 'ASC';
    const SORT_DESCENDING = 'DESC';
        
    /** Search fields */
    protected $searchFields;
    
    /** Fields to display */
    protected $displayFields;
    
    /** Sort field */
    protected $sortField;
    
    /** Sort order */
    protected $sortOrder;
    
    /** ID field */
    protected $idField;    
    
    /** Already set search filters */
    protected $searchFilters;
    
    /** Search results */
    protected $searchResults;

    /** Current page number */
    protected $pageNo;
    
    /** Page count */
    protected $pageCount;
    
    /** Match all or any */
    protected $matchType = self::MATCH_ALL;

    /**
     * Retrieves the value of searchFields.
     * @return searchFields
     */
    public function getSearchFields() {
        return $this->searchFields;
    }

    /**
     * Sets the value of searchFields.
     * @param searchFields
     */
    public function setSearchFields($searchFields) {
        $this->searchFields = $searchFields;
    }

    /**
     * Retrieves the value of displayFields.
     * @return displayFields
     */
    public function getDisplayFields() {
        return $this->displayFields;
    }

    /**
     * Sets the value of displayFields.
     * @param displayFields
     */
    public function setDisplayFields($displayFields) {
        $this->displayFields = $displayFields;
    }

    /**
     * Retrieves the value of sortField.
     * @return sortField
     */
    public function getSortField() {
        return $this->sortField;
    }

    /**
     * Sets the value of sortField.
     * @param sortField
     */
    public function setSortField($sortField) {
        $this->sortField = $sortField;
    }

    /**
     * Retrieves the value of sortOrder.
     * @return sortOrder
     */
    public function getSortOrder() {
        return $this->sortOrder;
    }

    /**
     * Sets the value of sortOrder.
     * @param sortOrder
     */
    public function setSortOrder($sortOrder) {
        $this->sortOrder = $sortOrder;
    }

    /**
     * Retrieves the value of idField.
     * @return idField
     */
    public function getIdField() {
        return $this->idField;
    }

    /**
     * Sets the value of idField.
     * @param idField
     */
    public function setIdField($idField) {
        $this->idField = $idField;
    }

    /**
     * Retrieves the value of searchFilters.
     * @return searchFilters
     */
    public function getSearchFilters() {
        return $this->searchFilters;
    }

    /**
     * Sets the value of searchFilters.
     * @param searchFilters
     */
    public function setSearchFilters($searchFilters) {
        $this->searchFilters = $searchFilters;
    }

    /**
     * Retrieves the value of searchResults.
     * @return searchResults
     */
    public function getSearchResults() {
        return $this->searchResults;
    }

    /**
     * Sets the value of searchResults.
     * @param searchResults
     */
    public function setSearchResults($searchResults) {
        $this->searchResults = $searchResults;
    }

    /**
     * Retrieves the value of pageNo.
     * @return pageNo
     */
    public function getPageNo() {
        return $this->pageNo;
    }

    /**
     * Sets the value of pageNo.
     * @param pageNo
     */
    public function setPageNo($pageNo) {
        $this->pageNo = $pageNo;
    }

    /**
     * Retrieves the value of pageCount.
     * @return pageCount
     */
    public function getPageCount() {
        return $this->pageCount;
    }

    /**
     * Sets the value of pageCount.
     * @param pageCount
     */
    public function setPageCount($pageCount) {
        $this->pageCount = $pageCount;
    }
        
    /**
     * Get the match type
     * @return String match type
     */
    public function getMatchType() {
        return $this->matchType;
    }
    
    /**
     * Set the match type
     * @param String $matchType Match type (one of MATCH_ALL or MATCH_ANY)
     */
    public function setMatchType($matchType) {
        $this->matchType = $matchType;
    }
    
    /**
     * Get SearchField with given field name from the fields associated with this search object.
     * 
     * @param String $fieldName Field Name
     * @return SearchField Search field object if found, or null
     */
    public function getFieldWithName($fieldName) {
        
        if (!empty($this->searchFields)) {
            foreach ($this->searchFields as $field) {
                
                if ($field->getFieldName() == $fieldName) {
                    return $field;
                }
            }    
        }
        
        return null;
    }
    
    /**
     * Perform search according to parameters set
     * and update the search results and count parameters
     * 
     * Should be implemented in implementing class
     * 
     * @return none
     */
    public abstract function search();  
}
?>
