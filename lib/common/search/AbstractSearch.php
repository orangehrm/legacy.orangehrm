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

require_once ROOT_PATH . '/lib/confs/sysConf.php';

require_once ROOT_PATH . '/lib/common/search/DisplayField.php';
require_once ROOT_PATH . '/lib/common/search/EditableField.php';
require_once ROOT_PATH . '/lib/common/search/SearchField.php';
require_once ROOT_PATH . '/lib/common/search/SearchFilter.php';
require_once ROOT_PATH . '/lib/common/search/SelectOption.php';

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

    /** Number of results */
    protected $numResults = 0;
    
    /** Current page number */
    protected $pageNo = 1;
    
    /** Items per page */
    protected $itemsPerPage;
    
    /** Page count */
    protected $pageCount = 0;    
    
    /** Match all or any */
    protected $matchType = self::MATCH_ALL;
    
    /** Bulk editing of results */
    protected $bulkEditAllowed = false;
    
    /** Inline editing of results */
    protected $inlineEditAllowed = false;
    
    /** Updates */
    protected $updates;
    
    /**
     * Constructor
     */
    public function __construct() {
        $sysConf = new sysConf();        
        $this->itemsPerPage = $sysConf->itemsPerPage;        
    }
    
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
     * Get the number of results
     * @return int number of results
     */
    public function getNumResults() {
        return $this->numResults;
    }
    
    /**
     * Set the number of results
     * @param int $numResults The number of results matching the search
     */
    public function setNumResults($numResults) {
        $this->numResults = $numResults;
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
     * Retrieves the value of items per page
     * @return int Items per page
     */
    public function getItemsPerPage() {
        return $this->itemsPerPage;
    }

    /**
     * Sets the items per page
     * @param int $itemsPerPage Items per page
     */
    public function setItemsPerPage($itemsPerPage) {
        $this->itemsPerPage = $itemsPerPage;
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
     * Whether to allow result editing bulk
     * @return boolean True if allowed, false if not
     */
    public function isBulkEditAllowed() {
        return $this->bulkEditAllowed;    
    }
    
    /**
     * Set bulk edit allowed parameter
     * @param boolean $allow 
     */
    public function setBulkEditAllowed($allow) {
        return $this->bulkEditAllowed = $allow;
    }

    /**
     * Whether to allow result editing inline
     * @return boolean True if allowed, false if not
     */
    public function isInlineEditAllowed() {
        return $this->inlineEditAllowed;    
    }
    
    /**
     * Set inline edit allowed parameter
     * @param boolean $allow 
     */
    public function setInlineEditAllowed($allow) {
        return $this->inlineEditAllowed = $allow;
    }
    
    /**
     * Get SearchField with given field name from the fields associated with this search object.
     * 
     * @param String $fieldName Field Name
     * @return SearchField Search field object if found, or null
     */
    public function getSearchFieldWithName($fieldName) {        
        return $this->_getFieldWithName($this->searchFields);    
    }
    
    /**
     * Get DisplayField with given field name from the display fields associated with this search object.
     * 
     * @param String $fieldName Field Name
     * @return DisplayField Display field object if found, or null
     */
    public function getDisplayFieldWithName($fieldName) {        
        return $this->_getFieldWithName($this->displayFields);
    }
    
    /**
     * Get DisplayField with given field name from the display fields associated with this search object.
     * 
     * @param String $fieldName Field Name
     * @return DisplayField Display field object if found, or null
     */
    private function _getFieldWithName($fieldList, $fieldName) {
        
        if (!empty($fieldList)) {
            foreach ($fieldList as $field) {                
                if ($field->getFieldName() == $fieldName) {
                    return $field;
                }
            }    
        }
        
        return null;
    }

    /**
     * Retrieves the Editable fields only from the displayFields.
     * @return displayFields
     */
    public function getEditableFields() {
        
        $editableFields = null;
        
        if (!empty($this->displayFields)) {
            foreach ($this->displayFields as $field) {                
                if ($field instanceof EditableField) {
                    $editableFields[] = $field;
                }
            }    
        }
        
        return $editableFields;
    }
    
    /**
     * Set updates 
     * @param Array of UpdatableFields indexed by ID
     */
    public function setUpdate($updates) {
        $this->updates = $updates;
    }
    
    /**
     * Get list updates
     * @return Array Array UpdatableField objects
     */
    public function getUpdates() {
        return $this->updates;
    }
    
    /**
     * Set bulk update
     * @param
     */
    public function setBulkUpdate($type, $fields, $values, $ids = null) {

    }
    
    public function getBulkUpdate() {
        
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
    
    /**
     * Perform any updates needed.
     * 
     * Should be implemented in implementing class
     * 
     * @return none
     */
     public abstract function updateData();
     
}
?>
