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
 
require_once ROOT_PATH . '/lib/common/search/AbstractSearch.php';
require_once ROOT_PATH . '/lib/common/search/DisplayField.php';
require_once ROOT_PATH . '/lib/common/search/SearchField.php';
require_once ROOT_PATH . '/lib/common/search/SearchFilter.php';
require_once ROOT_PATH . '/lib/common/search/SelectOption.php';
require_once ROOT_PATH . '/lib/models/eimadmin/Location.php';
require_once ROOT_PATH . '/lib/models/hrfunct/Employee.php';

/**
 * TODO: Move this to another directory, don't keep under models
 */
class EmployeeSearch extends AbstractSearch {
    
    public function __construct() {
                         
        $this->searchFields[] = new SearchField(Employee::FIELD_EMP_ID, 'lang_empview_employeeid', SearchField::FIELD_TYPE_STRING);
        $this->searchFields[] = new SearchField(Employee::FIELD_FIRSTNAME, 'lang_hremp_EmpFirstName', SearchField::FIELD_TYPE_STRING);
        $this->searchFields[] = new SearchField(Employee::FIELD_LASTNAME, 'lang_hremp_EmpLastName', SearchField::FIELD_TYPE_STRING);
        
        $selectOptions = array();
        $location = new models_eimadmin_Location();
        $locArray = $location->getLocCodes();
        
        if (!empty($locArray)) {
            foreach ($locArray as $loc) {
                $selectOptions[] = new SelectOption($loc[0], $loc[1]);
            }
        }
        
        $this->searchFields[] = new SearchField(Employee::FIELD_LOCATIONS, 'lang_hremp_EmployeeLocationOption', 
            SearchField::FIELD_TYPE_SELECT, 
            array(SearchField::OPERATOR_EQUAL, SearchField::OPERATOR_NOT_EQUAL), 
            $selectOptions);
        
        $this->displayFields[] = new DisplayField(Employee::FIELD_EMP_ID, 'lang_empview_employeeid');
        $this->displayFields[] = new DisplayField(Employee::FIELD_NAME, 'lang_empview_employeename');
        $this->sortField = Employee::FIELD_EMP_ID;
        $this->sortOrder = AbstractSearch::SORT_ASCENDING;
        $this->idField = Employee::FIELD_EMP_NUMBER;
        $this->searchFilters = null;
        $this->searchResults = null;
        $this->pageNo = 1;
        $this->pageCount = 0;        
    }
    
    /**
     * Perform search and set internal results array.
     */
    public function search() {       
        $this->searchResults = Employee::search($this->searchFilters, $this->matchType, $this->sortField, $this->sortOrder, $this->pageNo);        
    }
}
?>
