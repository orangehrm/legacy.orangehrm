<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeReport
 *
 * @author Faris
 */
class EmployeeReport extends Page {

    public $employeeName;
    public $projectName;
    public $activityName;
    public $dateRangeFrom;
    public $dateRangeTo;
    public $chkOnlyApprovedSheets;
    public $btnView;
    public $autoCompletionMenu;

    public function __construct($selenium) {
        parent::__construct($selenium);
        $this->config = new TestConfig();
        $this->employeeName = "//input[@id='employee_empName']";
        $this->projectName = "//select[@id='time_project_name']";
        $this->activityName = "//select[@id='time_activity_name']";
        $this->dateRangeFrom = "//input[@id='project_date_range_from_date']";
        $this->dateRangeTo = "//input[@id='project_date_range_to_date']";
        $this->chkOnlyApprovedSheets = "//input[@id='only_include_approved_timesheets']";
        $this->btnView = "//input[@id='viewbutton']";
        $this->autoCompletionMenu = "//li[@class='ac_even ac_over']";
    }
    
    private function waitForValueInComboBox($locator, $expectedValue, $timeOut){
        
        for ($i=0; $i < $timeOut; $i++){
            $values = $this->selenium->getSelectOptions($locator);
            if(!in_array($expectedValue, $values)){
                sleep(1);
                
            }  else {
                
                return true;
            }
            
        }
        return false;
        
    }

    
    
    public function viewEmployeeReport($employee, $projectName=null, $activityName=null, $dateRangeFrom=null, $dateRangeTo=null, $chkOnlyApprovedSheets=false) {
        $this->selenium->selectFrame("relative=top");
        
        $this->selenium->type($this->employeeName, $employee);
        $this->selenium->click($this->employeeName);
        if($this->selenium->isElementPresent($this->autoCompletionMenu))
            $this->selenium->click($this->autoCompletionMenu);
        
        if ($projectName != null) {
            if($this->waitForValueInComboBox($this->projectName, $projectName, $this->config->getTimeoutValue())){
            $this->selenium->select($this->projectName, $projectName);
            }
            
        }
        if ($activityName != null) {
            if($this->waitForValueInComboBox($this->activityName, $activityName, $this->config->getTimeoutValue())){
            $this->selenium->select($this->activityName, $activityName);
            }
        }
        if ($dateRangeFrom != null) {
            $this->selenium->type($this->dateRangeFrom, $dateRangeFrom);
        }

        if ($dateRangeTo) {
            $this->selenium->type($this->dateRangeTo, $dateRangeTo);
        }

        if ($chkOnlyApprovedSheets) {
            $this->selenium->click($this->chkOnlyApprovedSheets);
        }

        $this->selenium->clickAndWait($this->btnView);

        
    }

}