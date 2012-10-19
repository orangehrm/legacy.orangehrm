<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeListPage
 *
 * @author Chinthani
 */
class EmployeeListPage { 
    
    public $txtEmployeeName = "empsearch_employee_name_empName";
    public $txtEmployeeId = "empsearch_id";
    public $cmbEmployeeStatus = "empsearch_employee_status";
    public $cmbEmployeeTermination = "empsearch_termination";
    public $txtSupervisorName = "empsearch_supervisor_name";
    public $cmbJobTitle = "empsearch_job_title";
    public $cmbSubUnit = "empsearch_sub_unit";
    public $btnExpand = "link=>";
    public $btnSearch = "searchBtn";
    public $btnDelete = "btnDelete";
    public $btnDialogDelete = "dialogDeleteBtn";
    public $btnDialogCancel = "//div[@class='modal-footer']/input[@class='btn reset']"; 
    public $btnAdd = "btnAdd" ;
    public $btnReset = "resetBtn";
    public $btnmenu = "link=Employee List" ;


    public function __construct(FunctionalTestcase $selenium){
        $this->selenium = $selenium;
         $this->list = new BasicList($this->selenium, "//form[@id='frmList_ohrmListComponent']");
           }
           
//    public function clickDelete(){
//         $this->selenium->click($this->btnDelete);
//         $this->selenium->waitForPageToLoad(10);
//         $this->selenium->click($this->btnDialogDelete);      
//        
//    }        
      
     public function clickCancelDelete(){
         $this->selenium->click($this->btnDelete);
         $this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogCancel);      
        
    } 
    
                
           
    
    public function searchResetSearch($EmployeeName, $EmployeeId, $EmployeeStatus, $EmployeeTermination, $SupervisorName, $JobTitle, $SubUnit){
        //$this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnExpand);
        $this->selenium->type($this->txtEmployeeName, $EmployeeName);
        $this->selenium->type($this->txtEmployeeId, $EmployeeId);
        $this->selenium->select($this->cmbEmployeeStatus, $EmployeeStatus);
        $this->selenium->select($this->cmbEmployeeTermination, $EmployeeTermination);
        $this->selenium->type($this->txtSupervisorName, $SupervisorName);
        $this->selenium->select($this->cmbJobTitle, $JobTitle);
        $this->selenium->select($this->cmbSubUnit, $SubUnit);
        $this->selenium->click($this->btnSearch);
        $this->selenium->waitForPageToLoad(10);
        $this->selenium->click($this->btnExpand); 
        $this->selenium->click($this->btnReset); 
        //echo "No error on click search";
        $this->selenium->waitForPageToLoad(10);
        
        // return new EmployeeListPage($this->selenium);
        
    }
    
 //    public function searchBy($EmployeeId, $EmployeeStatus)
//       $this->selenium->click($this->btnExpand);
//       $this->selenium->type($this->txtEmployeeId, $EmployeeId);
//       $this->selenium->select($this->cmbEmployeeStatus, $EmployeeStatus);
//       $this->selenium->click($this->btnSearch);
//       $this->selenium->waitForPageToLoad(10);
         
         public function searchBy($EmployeeName, $EmployeeId, $EmployeeStatus, $EmployeeTermination, $SupervisorName, $JobTitle, $SubUnit){
        //$this->selenium->selectFrame("relative=top");
        $this->selenium->click($this->btnExpand);
        $this->selenium->type($this->txtEmployeeName, $EmployeeName);
        $this->selenium->type($this->txtEmployeeId, $EmployeeId);
        $this->selenium->select($this->cmbEmployeeStatus, $EmployeeStatus);
        $this->selenium->select($this->cmbEmployeeTermination, $EmployeeTermination);
        $this->selenium->type($this->txtSupervisorName, $SupervisorName);
        $this->selenium->select($this->cmbJobTitle, $JobTitle);
        $this->selenium->select($this->cmbSubUnit, $SubUnit);
        $this->selenium->click($this->btnSearch);
        //echo "No error on click search";
        $this->selenium->waitForPageToLoad(10);
        
        // return new EmployeeListPage($this->selenium);  
  }
       
     public function sortByFieldName($fieldName) {
         
         
         if ($fieldName == "Id"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[2]/a");
                }
         if ($fieldName == "First (& Middle) Name"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[3]/a");
         }
         if ($fieldName == "Last Name"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[4]/a");
         }
         if ($fieldName == "Job Title"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[5]/a");
         }
         if ($fieldName == "Employment Status"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[6]/a");
         }
         if ($fieldName == "Sub Unit"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[7]/a");
         }
         if ($fieldName == "Supervisor"){
            
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[8]/a");
               
             
         }
                 
    }    
    
    public  function getStatusMessage(){
         return $this->selenium->getText("//form[@id='frmList_ohrmListComponent']/table/tbody/tr/td");

    }
    
//    public  function getDeleteStatusMessage(){
//         return $this->selenium->getText("//form[@id='frmList_ohrmListComponent']/table/tbody/tr/td");
//
//    }
    

     public function getEmployeeList(){
        
        return $this->list;
        
    }
    
    public function goToNewEmployeeList(){
        $this->selenium->click($this->btnmenu);
        $this->selenium->waitForPageToLoad(10);
    }
   
    public function goToNewEmployeeListSearch(){
        $this->selenium->click($this->btnmenu);
        $this->selenium->waitForPageToLoad(10);
        $this->selenium->click($this->btnExpand);
    }
    
    public function goToAddEmployee(){
        $this->selenium->click($this->btnAdd);
    }
            
//    public function getHeading() {
//        Helper::$selenium = $this->selenium;
//        return Helper::getTitle();
//    }

    
}

?>
