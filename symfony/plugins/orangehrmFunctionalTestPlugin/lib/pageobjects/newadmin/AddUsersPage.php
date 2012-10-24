<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddUsersPage
 *
 * @author chinthani
 */
class AddUsersPage {
   
    public $cmbUserType = "systemUser_userType";
    public $txtEmployeeName = "systemUser_employeeName_empName";
    public $txtUsername = "systemUser_userName";
    public $txtPassword = "systemUser_password";
    public $txtConfirmPassword = "systemUser_confirmPassword";
    public $cmbStatus = "systemUser_status";
    public $btnSave = "btnSave";
    public $btnCancel = "btnCancel";
    
    
    
    public function __construct(FunctionalTestcase $selenium){
        $this->selenium = $selenium;
        $this->list = new BasicList($this->selenium, "//form[@id='frmList_ohrmListComponent']");
           }    
  
    
    public function addUser($UserType, $EmployeeName, $Username, $Status,$Password, $ConfirmPassword){
        $this->selenium->select($this->cmbUserType, $UserType);
        $this->selenium->type($this->txtEmployeeName, $EmployeeName);
        $this->selenium->type($this->txtUsername, $Username);
        $this->selenium->select($this->cmbStatus, $Status);
        $this->selenium->type($this->txtPassword, $Password);
        $this->selenium->type($this->txtConfirmPassword, $ConfirmPassword);
        $this->selenium->click($this->btnSave);
        $this->selenium->waitForPageToLoad(10);  
    }  
    
    
        public function cancelAddUser($UserType, $EmployeeName, $Username, $Status,$Password, $ConfirmPassword){
        $this->selenium->select($this->cmbUserType, $UserType);
        $this->selenium->type($this->txtEmployeeName, $EmployeeName);
        $this->selenium->type($this->txtUsername, $Username);
        $this->selenium->select($this->cmbStatus, $Status);
        $this->selenium->type($this->txtPassword, $Password);
        $this->selenium->type($this->txtConfirmPassword, $ConfirmPassword);
        $this->selenium->click($this->btnCancel);
        $this->selenium->waitForPageToLoad(10);  
    } 
    
    
}

?>
