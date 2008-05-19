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
require_once ROOT_PATH . '/lib/models/eimadmin/JobTitle.php';
require_once ROOT_PATH . '/lib/models/eimadmin/Location.php';
require_once ROOT_PATH . '/lib/models/hrfunct/Employee.php';
require_once ROOT_PATH . '/lib/models/eimadmin/EmployStat.php';   
require_once ROOT_PATH . '/lib/models/eimadmin/CompStruct.php';

/**
 * TODO: Move this to another directory, don't keep under models
 */
class EmployeeSearch extends AbstractSearch {
    
    public function __construct() {
                         
        parent::__construct();
                                 
        $this->searchFields[] = new SearchField(Employee::FIELD_EMP_ID, 'lang_empview_employeeid', DataField::FIELD_TYPE_STRING);
        $this->searchFields[] = new SearchField(Employee::FIELD_FIRSTNAME, 'lang_hremp_EmpFirstName', DataField::FIELD_TYPE_STRING);
        $this->searchFields[] = new SearchField(Employee::FIELD_LASTNAME, 'lang_hremp_EmpLastName', DataField::FIELD_TYPE_STRING);
        
        /* Add search by location */
        $locationOptions = array();
        $location = new models_eimadmin_Location();
        $locArray = $location->getLocCodes();
        
        if (!empty($locArray)) {
            foreach ($locArray as $loc) {
                $locationOptions[] = new SelectOption($loc[0], $loc[1]);
            }
        }
        
        $this->searchFields[] = new SearchField(Employee::FIELD_LOCATIONS, 'lang_hremp_EmployeeLocationOption', 
            DataField::FIELD_TYPE_SELECT, null, $locationOptions);

        /* Add search by job title */
        $jobTitleOptions = array();
        $jobTitle = new JobTitle();
        $jobTitles = $jobTitle->getJobTit();
        if (!empty($jobTitles)) {
            foreach ($jobTitles as $job) {
                $jobTitleOptions[] = new SelectOption($job[0], $job[1]);
            }            
        }
            
        $this->searchFields[] = new SearchField(Employee::FIELD_JOB_TITLE, 'lang_hremp_jobtitle',  
            DataField::FIELD_TYPE_SELECT, null, $jobTitleOptions);
            
        /* Add search by employee status */
        $empStatusOptions = array();
        $empStatus = new EmploymentStatus();
        $statusList = $empStatus->getEmpStat();
        if (!empty($statusList)) {
            foreach ($statusList as $stat) {
                $empStatusOptions[] = new SelectOption($stat[0], $stat[1]);
            }            
        }
            
        $this->searchFields[] = new SearchField(Employee::FIELD_EMP_STATUS, 'lang_hremp_EmpStatus',  
            DataField::FIELD_TYPE_SELECT, null, $empStatusOptions);

        /* Search by employee sub division (work station) */
        $subDivisionOptions = array();
        $compStruct = new CompStruct();
        $compStruct->buildAllWorkStations();
        $subdivisionList = $compStruct->getHierachyArr();
        if (!empty($subdivisionList)) {
            foreach ($subdivisionList as $div) {
            	if (!empty($div['title'])) {
	                $hierLevel = $compStruct->getHierachyLevel($div['id']); 
	                $title = $indentStr = str_repeat("- ", $hierLevel) .  $div['title'];
	                $subDivisionOptions[] = new SelectOption($div['id'], $title);
            	}
            }
        }
        
        $this->searchFields[] = new SearchField(Employee::FIELD_SUB_DIVISION, 'lang_hremp_EmployeeSubDivisionOption',  
            DataField::FIELD_TYPE_SELECT, null, $subDivisionOptions);
                    
        $this->displayFields[] = new EditableField(Employee::FIELD_EMP_ID, 'lang_empview_employeeid', DataField::FIELD_TYPE_STRING);
        $this->displayFields[] = new DisplayField(Employee::FIELD_NAME, 'lang_empview_employeename', DataField::FIELD_TYPE_STRING);
        $this->sortField = Employee::FIELD_EMP_ID;
        $this->sortOrder = AbstractSearch::SORT_ASCENDING;
        $this->idField = Employee::FIELD_EMP_NUMBER;
        $this->inlineEditAllowed = true;
        $this->bulkEditAllowed = true;
        $this->searchFilters = null;
        $this->searchResults = null;
        $this->pageNo = 1;
        $this->pageCount = 0;        
        $this->numResults = 0;
    }
    
    /**
     * Perform search and set internal results array.
     */
    public function search() {       

        $this->searchResults = Employee::search($this->searchFilters, $this->matchType, $this->sortField, $this->sortOrder, $this->pageNo, $this->itemsPerPage);
        $this->numResults = Employee::countResults($this->searchFilters, $this->matchType);
        
        $this->pageCount = ($this->numResults / $this->itemsPerPage);        
        if ($this->numResults % $this->itemsPerPage) {
           $this->pageCount++;
        }                           
    }
    
    /**
     * Perform any needed data updates
     */
    public function updateData() {
        if (!empty($this->updates)) {
            Employee::update($this->updates);
        }
    }
}
?>
