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
require_once ROOT_PATH . '/lib/common/search/AbstractSearch.php';
require_once ROOT_PATH . '/lib/common/search/SearchFilter.php';
 
 class EXTRACTOR_Search {

    /**
     * Parse search data.
     * 
     * @param Array $postArr Array containing POST variables
     * @param Object Search Object extended from AbstractSearch
     * 
     * @return Object Search Object with parameters set according to values in POST
     */
	public function parseSearch($postArr, $searchObj) {
        
        if (empty($postArr)) {
            return $searchObj;
        }

        if (isset($postArr['sortBy']) && !empty($postArr['sortBy'])) {
            $searchObj->setSortField($postArr['sortBy']);
        }
        
        if (isset($postArr['sortOrder']) && !empty($postArr['sortOrder'])) {
            $searchObj->setSortOrder($postArr['sortOrder']);
        }

        if (isset($postArr['match']) && !empty($postArr['match'])) {
            $match = $postArr['match'];

            if (($match == AbstractSearch::MATCH_ALL) || ($match == AbstractSearch::MATCH_ANY)) {
                $searchObj->setMatchType($match);
            }
        }

        /* Parse search filters */
        $searchFilters = array();
        if (isset($postArr['searchField']) && isset($postArr['operator']) && isset($postArr['searchValue'])) {           

            $fields = $postArr['searchField'];
            $operators = $postArr['operator'];
            $values = $postArr['searchValue'];
                        
            $numFields = count($fields);
            for ($i = 0; $i < $numFields; $i++) {

                /* Check that operator and value are available as well */                
                if (isset($operators[$i])) { //}&& ($operators[$i]) isset($values[$i])) {
                    $fieldName = $fields[$i];
                    $operator = $operators[$i];
                    $searchOperator = SearchOperator::getOperator($operator);
                    
                    if (!$searchOperator->isBinary()) {
                        
                        /* No value needed for unary operators */
                        $value = null;
                    } else if (isset($values[$i])) {
                        $value = $values[$i];
                    } else {
                        
                        /* No value found. Skip this row */
                        continue;
                    }
                    
                    if (($fieldName != '-1') && !empty($operator)) {
                        $searchField = $searchObj->getSearchFieldWithName($fieldName);
                        if (!empty($searchField)) {
                            $searchFilters[] = new SearchFilter($searchField, $searchOperator, $value);                         
                        }
                    }
                }
            }
        }
        $searchObj->setSearchFilters($searchFilters);
        
        /* Page number */
        $pageNo = 1;
        if (isset($postArr['pageNo']) && (intval($postArr['pageNo']) > 0)) {
            $pageNo = $postArr['pageNo'];
        }
        $searchObj->setPageNo($pageNo);
        
        /* Updates - inline */
        if (isset($postArr['updateMode']) && ($postArr['updateMode'] == 'inline') && isset($postArr['id'])) {
            $ids = $postArr['id'];
            $numIds = count($ids);
            
            $updates = array();
            
            foreach ($searchObj->getEditableFields() as $editableField) {
                $fieldName = $editableField->getFieldName();
                $postVarName = 'in_' . $fieldName;
                if (isset($postArr[$postVarName]) && !empty($postArr[$postVarName])) {
                    
                    $newValues = $postArr[$postVarName];
                    if ($numIds == count($newValues)) {
                        for ($i = 0; $i < $numIds; $i++) {
                            $value = $newValues[$i];
                            $id = $ids[$i];
                            
                            $updates[$i][] = new UpdatableField($fieldName, $editableField->getFieldType(), $value);                
                        }
                    }                    
                }
            }
            
            $searchObj->setUpdates($updates);                        
        }
                
        /* Updates - Bulk */
        if (isset($postArr['updateMode']) && ($postArr['updateMode'] == 'bulk') && isset($postArr['id'])) {
        
        }
                        
        return $searchObj;
	}

}
?>