<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersListPage
 *
 * @author chinthani
 */
class UsersListPage {
    public $txtEmployeeName = "searchSystemUser_employeeName_empName";
    public $cmbUserType = "searchSystemUser_userType";
    public $txtUsername = "searchSystemUser_userName";
    public $cmbStatus = "searchSystemUser_status";
    public $btnSearch = "searchBtn";
    public $btnReset = "resetBtn";
    public $btnAdd = "btnAdd";
    public $btnExpand = "link=>";
    public $btnDelete = "btnDelete";
    public $btnDialogDelete = "dialogDeleteBtn";
   public $btnSelectAll ="ohrmList_chkSelectAll";
    public $dialogCancelBtn = "//div[@class='modal-footer']/input[@class='btn reset']";
    
    
    public function __construct(FunctionalTestcase $selenium){
        $this->selenium = $selenium;
        $this->list = new BasicList($this->selenium, "//form[@id='frmList_ohrmListComponent']");
           }    
    
        public function searchBy($Username, $UserType, $EmployeeName, $Status ){
          $this->selenium->click($this->btnExpand);
        $this->selenium->type($this->txtUsername, $Username);
        $this->selenium->select($this->cmbUserType, $UserType);
        $this->selenium->type($this->txtEmployeeName, $EmployeeName);
        $this->selenium->select($this->cmbStatus, $Status);
        $this->selenium->click($this->btnSearch);
        $this->selenium->waitForPageToLoad(10);
        
          
  }
  
        public function searchAndReset($Username, $UserType, $EmployeeName, $Status ){
        $this->selenium->click($this->btnExpand);
        $this->selenium->type($this->txtUsername, $Username);
        $this->selenium->select($this->cmbUserType, $UserType);
        $this->selenium->type($this->txtEmployeeName, $EmployeeName);
        $this->selenium->select($this->cmbStatus, $Status);
        $this->selenium->click($this->btnSearch);
         $this->selenium->waitForPageToLoad(10);
        $this->selenium->click($this->btnExpand);
        $this->selenium->click($this->btnReset);
        $this->selenium->waitForPageToLoad(10);
        
          
  }
  
       public function getUsersList(){
        
        return $this->list;
        
    }
    
        public function goToUsersListSearch(){
        $this->selenium->click($this->btnExpand);
         //return new UsersListPage($selenium);
    }
    
    public function sortByFieldName($fieldName) {
         
         
         if ($fieldName == "Username"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[2]/a");
                }
         if ($fieldName == "User Type"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[3]/a");
         }
         if ($fieldName == "Employee Name"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[4]/a");
         }
         if ($fieldName == "Status"){
             
             $this->selenium->click("//form[@id='frmList_ohrmListComponent']/table/thead/tr/th[5]/a");
         }
    }
 
        public function clickDelete(){
         $this->selenium->click($this->btnDelete);
         $this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogDelete);      
        
    }        
      
     public function clickCancelDelete(){
         $this->selenium->click($this->btnDelete);
         $this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogCancel);      
        
    } 
    
        public function goToAddUser(){
        $this->selenium->click($this->btnAdd);
    }
    
  public  function getStatusMessage(){
         return $this->selenium->getText("//form[@id='frmList_ohrmListComponent']/table/tbody/tr/td");

    }  
    
        public function deleteAllUsers() {
         $this->selenium->click($this->btnSelectAll);
         $this->selenium->click($this->btnDelete);
         //$this->selenium->waitForPageToLoad(10);
         $this->selenium->click($this->btnDialogDelete);
    }
    
    
    
}

?>
