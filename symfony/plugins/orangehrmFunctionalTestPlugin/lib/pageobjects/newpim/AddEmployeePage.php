<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddEmployeePage
 *
 * @author chinthani
 */
class AddEmployeePage {
    
    public $txtFirstName = "firstName";
    public $txtMiddleName = "middleName";
    public $txtLastName = "lastName";
    public $txtEmployeeId = "employeeId";
    public $txtPhotofile = "photofile";
    public $chkLogin= "chkLogin";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    public $txtUserName = "user_name";
    public $txtPassword = "user_password";
    public $txtConfirmPassword = "re_password";
    public $cmbStatus = "status";
    


    public function __construct(FunctionalTestcase $selenium){
        $this->selenium = $selenium;
         $this->list = new BasicList($this->selenium, "//form[@id='frmList_ohrmListComponent']");
           }
    
    public function addEmployee($FirstName, $LastName) {
        $this->selenium->type($this->txtFirstName, $FirstName);
        $this->selenium->type($this->txtLastName, $LastName);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10); 
    }                   
           
    public function addEmployeeWithLoginCredentialAndPhotograph($FirstName, $MiddleName, $LastName, $EmployeeId, $Photofile, $UserName, $Password, $ConfirmPassword, $Status){
        $this->selenium->type($this->txtFirstName, $FirstName);
        $this->selenium->type($this->txtMiddleName, $MiddleName);
        $this->selenium->type($this->txtLastName, $LastName);
        $this->selenium->type($this->txtEmployeeId, $EmployeeId);
        $this->selenium->type($this->txtPhotofile, $Photofile);
        $this->selenium->click($this->chkLogin);
        $this->selenium->type($this->txtUserName, $UserName);
        $this->selenium->type($this->txtPassword, $Password); 
        $this->selenium->type($this->txtConfirmPassword, $ConfirmPassword);
        $this->selenium->select($this->cmbStatus, $Status);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);  
    }  
           
           
           
           
           
           
           
           
}
?>
