<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmploeeInformationPage
 *
 * @author chinthani
 */
//INCORRECT
class EmployeeInformationPage {
        
    public $txtEmployeeName = "empsearch_employee_name_empName";
    public $txtEmployeeId = "empsearch_id";
    public $cmbEmployeeStatus = "empsearch_employee_status";
    public $cmbEmployeeTermination = "empsearch_termination";
    public $txtSupervisorName = "empsearch_supervisor_name";
    public $cmbJobTitle = "empsearch_job_title";
    public $cmbSubUnit = "empsearch_sub_unit";
    public $btnExpand = "link=>";
    public $btnSearch = "searchBtn";
    


    public function __construct(FunctionalTestcase $selenium){
        $this->selenium = $selenium;
         $this->list = new BasicList($this->selenium, "//form[@id='frmList_ohrmListComponent']");
        
    }
    
//    public function search($EmployeeName, $EmployeeId, $EmployeeStatus, $EmployeeTermination, $SupervisorName, $JobTitle, $SubUnit){
//        //$this->selenium->selectFrame("relative=top");
//        $this->selenium->click($this->btnExpand);
//        $this->selenium->type($this->txtEmployeeName, $EmployeeName);
//        $this->selenium->type($this->txtEmployeeId, $EmployeeId);
//        $this->selenium->select($this->cmbEmployeeStatus, $EmployeeStatus);
//        $this->selenium->select($this->cmbEmployeeTermination, $EmployeeTermination);
//        $this->selenium->type($this->txtSupervisorName, $SupervisorName);
//        $this->selenium->select($this->cmbJobTitle, $JobTitle);
//        $this->selenium->select($this->cmbSubUnit, $SubUnit);
//        $this->selenium->click($this->btnSearch);
//        //echo "No error on click search";
//        $this->selenium->waitForPageToLoad(10);
        
        // return new EmployeeListPage($this->selenium);
        
 //   }    
    public function searchBy($EmployeeId, $EmployeeStatus){
       $this->selenium->click($this->btnExpand);
       $this->selenium->type($this->txtEmployeeId, $EmployeeId);
       $this->selenium->select($this->cmbEmployeeStatus, $EmployeeStatus);
       $this->selenium->click($this->btnSearch);
       $this->selenium->waitForPageToLoad(10);
        
  }
     public function getEmployeList(){
        
        return $this->list;
        
    }
    //put your code here
}

?>
